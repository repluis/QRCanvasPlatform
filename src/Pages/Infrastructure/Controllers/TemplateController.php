<?php

namespace Src\Pages\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Pages\Application\Actions\CreatePageFromTemplateAction;
use Src\Pages\Domain\Templates\TemplateRegistry;

class TemplateController
{
    public function __construct(
        private readonly TemplateRegistry $registry,
        private readonly CreatePageFromTemplateAction $createFromTemplate,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'templates' => $this->registry->listForApi(),
        ]);
    }

    public function create(Request $request, string $template): JsonResponse
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        Log::info('[TemplateController] instantiate template', [
            'template' => $template,
            'user_id' => $userId,
        ]);

        try {
            $page = $this->createFromTemplate->execute($template, $userId);

            return response()->json([
                'message' => 'Page created from template',
                'page' => [
                    'id' => $page->getId(),
                    'uuid' => $page->getUuid(),
                    'title' => $page->getTitle(),
                    'slug' => $page->getSlug(),
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            Log::error('[TemplateController] failed to instantiate template', [
                'template' => $template,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to create page from template'], 500);
        }
    }
}
