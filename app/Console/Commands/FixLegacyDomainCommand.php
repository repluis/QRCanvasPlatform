<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Pages\Infrastructure\Persistence\Models\PageModel;

/**
 * One-off cleanup: some pages were created while APP_URL still pointed at
 * the wrong Render domain (qrcanvas-platform, with a hyphen), so their QR
 * content/image URLs baked in that stale host. Safe to run repeatedly —
 * it's a no-op once no page contains the old domain anymore.
 */
class FixLegacyDomainCommand extends Command
{
    protected $signature = 'pages:fix-domain';

    protected $description = 'Replace the stale qrcanvas-platform.onrender.com domain baked into existing pages\' QR data';

    public function handle(): int
    {
        $old = 'https://qrcanvas-platform.onrender.com';
        $new = config('app.url');

        if (!$new || $new === $old) {
            $this->error('APP_URL is not set to the correct domain — aborting.');
            return self::FAILURE;
        }

        $fixedCount = 0;

        PageModel::query()->whereNotNull('canvases')->each(function (PageModel $page) use ($old, $new, &$fixedCount) {
            $json = json_encode($page->canvases, JSON_UNESCAPED_SLASHES);
            $fixed = str_replace([$old, urlencode($old)], [$new, urlencode($new)], $json);

            if ($fixed !== $json) {
                $page->canvases = json_decode($fixed, true);
                $page->save();
                $fixedCount++;
                $this->line("Fixed: {$page->uuid}");
            }
        });

        $this->info("Done. Fixed {$fixedCount} page(s).");

        return self::SUCCESS;
    }
}
