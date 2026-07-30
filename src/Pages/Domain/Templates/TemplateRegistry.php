<?php

namespace Src\Pages\Domain\Templates;

use Illuminate\Support\Facades\Log;

/**
 * Discovers all PageTemplate implementations under the Presets folder.
 *
 * Templates are registered automatically by file presence — to add a new
 * one, drop a class file in Presets/ that implements PageTemplate. No central
 * registry list to update.
 */
class TemplateRegistry
{
    /** @var array<string, PageTemplate>|null */
    private ?array $cache = null;

    /** @return PageTemplate[] */
    public function all(): array
    {
        if ($this->cache !== null) {
            return array_values($this->cache);
        }

        $this->cache = [];

        $presetDir = __DIR__ . '/Presets';
        if (!is_dir($presetDir)) {
            Log::warning('[TemplateRegistry] Presets dir missing', ['path' => $presetDir]);
            return [];
        }

        foreach (scandir($presetDir) as $file) {
            if (!str_ends_with($file, '.php')) continue;

            $class = __NAMESPACE__ . '\\Presets\\' . substr($file, 0, -4);
            if (!class_exists($class)) continue;
            if (!is_subclass_of($class, PageTemplate::class)) continue;

            try {
                /** @var PageTemplate $instance */
                $instance = app($class);
                $this->cache[$instance->id()] = $instance;
            } catch (\Throwable $e) {
                Log::error('[TemplateRegistry] failed to load template', [
                    'class' => $class,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        ksort($this->cache);
        return array_values($this->cache);
    }

    public function find(string $id): ?PageTemplate
    {
        foreach ($this->all() as $template) {
            if ($template->id() === $id) {
                return $template;
            }
        }
        return null;
    }

    /**
     * @return array<int, array{id:string, name:string, description:string, emoji:string}>
     */
    public function listForApi(): array
    {
        return array_map(fn (PageTemplate $t) => [
            'id' => $t->id(),
            'name' => $t->name(),
            'description' => $t->description(),
            'emoji' => $t->emoji(),
        ], $this->all());
    }
}
