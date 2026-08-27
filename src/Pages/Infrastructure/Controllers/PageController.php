<?php

namespace Src\Pages\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Src\Pages\Application\Actions\GetPageAction;
use Src\Pages\Application\Actions\GetUserPagesAction;
use Src\Pages\Application\Actions\SavePageAction;
use Src\Pages\Application\Actions\TogglePageStatusAction;
use Src\Pages\Application\DTO\SavePageDTO;
use Src\Shared\Infrastructure\Controllers\BaseController;

class PageController extends BaseController
{
    public function __construct(
        private readonly SavePageAction $savePageAction,
        private readonly GetPageAction $getPageAction,
        private readonly GetUserPagesAction $getUserPagesAction,
        private readonly TogglePageStatusAction $togglePageStatusAction,
    ) {}

    private function buildPageResponse($found): array
    {
        $response = [
            'id' => $found->getId(),
            'uuid' => $found->getUuid(),
            'title' => $found->getTitle(),
            'slug' => $found->getSlug(),
        ];

        if ($found->hasCanvases()) {
            $response['canvases'] = $found->getCanvases();
        } else {
            $response['canvases'] = [[
                'elements' => $found->getElements(),
                'background' => $found->getBackground(),
                'width' => 800,
                'height' => 600,
                'visible' => true,
            ]];
        }

        return $response;
    }

    public function editor(Request $request)
    {
        Log::info('[PageController] editor page requested', [
            'uuid' => $request->query('uuid'),
            'user_id' => auth()->id(),
        ]);

        $userPages = $this->getUserPagesAction->execute(auth()->id());

        $page = null;
        if ($uuid = $request->query('uuid')) {
            $found = $this->getPageAction->byUuid($uuid);
            if ($found) {
                $page = $this->buildPageResponse($found);
                Log::info('[PageController] page loaded', [
                    'uuid' => $uuid,
                    'canvases_count' => count($page['canvases'] ?? []),
                ]);
            } else {
                Log::warning('[PageController] page not found', ['uuid' => $uuid]);
            }
        }

        return Inertia::render('Pages/Views/Editor', [
            'images' => config('editor.images'),
            'userPages' => array_map(fn ($p) => [
                'id' => $p->getId(),
                'uuid' => $p->getUuid(),
                'title' => $p->getTitle(),
                'slug' => $p->getSlug(),
                'updated_at' => $p->getUpdatedAt()?->diffForHumans(),
            ], $userPages),
            'page' => $page,
        ]);
    }

    public function save(Request $request)
    {
        $userId = auth()->id();
        $title = $request->input('title');
        $slug = $request->input('slug');
        $canvases = $request->input('canvases', []);
        $hasId = !empty($request->input('id'));

        Log::info('[PageController] save page solicitado', [
            'user_id' => $userId,
            'title' => $title,
            'slug' => $slug,
            'canvases_count' => count($canvases),
            'has_id' => $hasId,
        ]);

        $dto = new SavePageDTO(
            title: $title ?? 'Sin titulo',
            elements: $request->input('elements', []),
            canvases: $canvases,
            slug: $slug ?? ('page-' . uniqid()),
            background: $request->input('background', '#ffffff'),
            id: $request->integer('id', null) ?: null,
            userId: auth()->id(),
        );

        try {
            $page = $this->savePageAction->execute($dto);

            Log::info('[PageController] page guardada exitosamente', [
                'id' => $page->getId(),
                'uuid' => $page->getUuid(),
            ]);

            return response()->json([
                'message' => 'Page saved successfully',
                'page' => $this->buildPageResponse($page),
            ]);
        } catch (\Throwable $e) {
            Log::error('[PageController] error guardando page', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error al guardar la pagina',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $uuid = $request->query('uuid');
        if (!$uuid) {
            Log::warning('[PageController] show page sin uuid');
            abort(404);
        }

        Log::info('[PageController] show page solicitado', ['uuid' => $uuid]);

        $page = $this->getPageAction->byUuid($uuid);

        if (!$page) {
            Log::warning('[PageController] page no encontrada', ['uuid' => $uuid]);
            abort(404);
        }

        if (!$page->getStatus()) {
            Log::info('[PageController] page deshabilitada, mostrando 404', ['uuid' => $uuid]);
            abort(404);
        }

        return Inertia::render('Pages/Views/Show', [
            'page' => $this->buildPageResponse($page),
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $userId = auth()->id();
        $pageId = $request->integer('id');

        if (!$pageId) {
            return response()->json(['error' => 'Missing page id'], 422);
        }

        try {
            $page = $this->togglePageStatusAction->execute($pageId, $userId);

            return response()->json([
                'message' => 'Status updated',
                'page' => [
                    'id' => $page->getId(),
                    'uuid' => $page->getUuid(),
                    'status' => $page->getStatus(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[PageController] toggleStatus error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
