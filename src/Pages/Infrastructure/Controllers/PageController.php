<?php

namespace Src\Pages\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Src\Pages\Application\Actions\GetPageAction;
use Src\Pages\Application\Actions\GetUserPagesAction;
use Src\Pages\Application\Actions\SavePageAction;
use Src\Pages\Application\DTO\SavePageDTO;
use Src\Shared\Infrastructure\Controllers\BaseController;

class PageController extends BaseController
{
    public function __construct(
        private readonly SavePageAction $savePageAction,
        private readonly GetPageAction $getPageAction,
        private readonly GetUserPagesAction $getUserPagesAction,
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
            ]];
        }

        return $response;
    }

    public function editor(Request $request)
    {
        $userPages = $this->getUserPagesAction->execute(auth()->id());

        $page = null;
        if ($uuid = $request->query('uuid')) {
            $found = $this->getPageAction->byUuid($uuid);
            if ($found) {
                $page = $this->buildPageResponse($found);
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
        $dto = new SavePageDTO(
            title: $request->input('title', 'Sin título'),
            elements: $request->input('elements', []),
            canvases: $request->input('canvases', []),
            slug: $request->input('slug', 'page-' . uniqid()),
            background: $request->input('background', '#ffffff'),
            id: $request->integer('id', null) ?: null,
            userId: auth()->id(),
        );

        $page = $this->savePageAction->execute($dto);

        return response()->json([
            'message' => 'Page saved successfully',
            'page' => $this->buildPageResponse($page),
        ]);
    }

    public function show(Request $request)
    {
        $uuid = $request->query('uuid');
        if (!$uuid) abort(404);

        $page = $this->getPageAction->byUuid($uuid);

        if (!$page) {
            abort(404);
        }

        return Inertia::render('Pages/Views/Show', [
            'page' => $this->buildPageResponse($page),
        ]);
    }
}
