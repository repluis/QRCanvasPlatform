<?php

namespace Src\Pages\Application\Actions;

use Illuminate\Support\Facades\Log;
use Src\Pages\Application\DTO\SavePageDTO;
use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Templates\TemplateRegistry;

/**
 * Creates a new page by instantiating a template for the given user.
 *
 * Performs the two-step dance described in TEMPLATES.md:
 *   1. Save the page (placeholder QR) → UUID is generated.
 *   2. Patch the QR element with the real URL → save again (UPDATE).
 */
class CreatePageFromTemplateAction
{
    public function __construct(
        private readonly TemplateRegistry $registry,
        private readonly SavePageAction $savePageAction,
    ) {}

    public function execute(string $templateId, int $userId): Page
    {
        $template = $this->registry->find($templateId);
        if (!$template) {
            throw new \InvalidArgumentException("Template '{$templateId}' not found");
        }

        $origin = config('app.url') ?: 'http://localhost:8000';

        $canvases = $template->canvases();
        $firstCanvasBg = $canvases[0]['background'] ?? '#ffffff';

        // Inject a placeholder QR into the first (hidden) canvas
        $canvases[0]['elements'] = [
            [
                'id' => 'qr-' . uniqid(),
                'type' => 'qr',
                'x' => 100, 'y' => 100, 'width' => 200, 'height' => 200,
                'content' => 'placeholder',
                'qrImageUrl' => 'placeholder',
                'foregroundColor' => '#be185d',
                'backgroundColor' => '#ffffff',
                'errorCorrectionLevel' => 'medium',
            ],
        ];

        // Step 1: insert
        $dto = new SavePageDTO(
            title: $template->defaultTitle(),
            elements: [],
            canvases: $canvases,
            slug: $template->id() . '-' . uniqid(),
            background: $firstCanvasBg,
            userId: $userId,
        );
        $page = $this->savePageAction->execute($dto);
        $uuid = $page->getUuid();

        Log::info('[CreatePageFromTemplateAction] page created from template', [
            'template' => $template->id(),
            'page_uuid' => $uuid,
            'user_id' => $userId,
        ]);

        // Step 2: patch the QR
        $canvases[0]['elements'][0] = [
            'id' => 'qr-' . $uuid,
            'type' => 'qr',
            'x' => 100, 'y' => 100, 'width' => 200, 'height' => 200,
            'content' => $origin . '/page?uuid=' . $uuid,
            'qrImageUrl' => sprintf(
                'https://quickchart.io/qr?text=%s&size=200&margin=2&dark=be185d&light=ffffff',
                urlencode($origin . '/page?uuid=' . $uuid)
            ),
            'foregroundColor' => '#be185d',
            'backgroundColor' => '#ffffff',
            'errorCorrectionLevel' => 'medium',
        ];

        $dto2 = new SavePageDTO(
            title: $template->defaultTitle(),
            elements: [],
            canvases: $canvases,
            slug: $page->getSlug(),
            background: $firstCanvasBg,
            id: $page->getId(),
            userId: $userId,
        );
        $page = $this->savePageAction->execute($dto2);

        return $page;
    }
}
