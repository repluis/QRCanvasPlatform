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

    public function editor(Request $request)
    {
        $userPages = $this->getUserPagesAction->execute(auth()->id());

        $page = null;
        if ($uuid = $request->query('uuid')) {
            $found = $this->getPageAction->byUuid($uuid);
            if ($found) {
                $page = [
                    'id' => $found->getId(),
                    'uuid' => $found->getUuid(),
                    'title' => $found->getTitle(),
                    'slug' => $found->getSlug(),
                    'elements' => $found->getElements(),
                ];
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
            slug: $request->input('slug', 'page-' . uniqid()),
            id: $request->integer('id', null) ?: null,
            userId: auth()->id(),
        );

        $page = $this->savePageAction->execute($dto);

        return response()->json([
            'message' => 'Page saved successfully',
            'page' => [
                'id' => $page->getId(),
                'uuid' => $page->getUuid(),
                'title' => $page->getTitle(),
                'slug' => $page->getSlug(),
                'elements' => $page->getElements(),
            ],
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
            'page' => [
                'id' => $page->getId(),
                'uuid' => $page->getUuid(),
                'title' => $page->getTitle(),
                'elements' => $page->getElements(),
                'slug' => $page->getSlug(),
            ],
        ]);
    }
}
