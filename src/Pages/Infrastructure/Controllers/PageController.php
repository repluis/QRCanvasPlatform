<?php

namespace Src\Pages\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Src\Pages\Application\Actions\GetPageAction;
use Src\Pages\Application\Actions\SavePageAction;
use Src\Pages\Application\DTO\SavePageDTO;
use Src\Shared\Infrastructure\Controllers\BaseController;

class PageController extends BaseController
{
    public function __construct(
        private readonly SavePageAction $savePageAction,
        private readonly GetPageAction $getPageAction,
    ) {}

    public function editor()
    {
        return Inertia::render('Pages/Views/Editor', [
            'images' => config('editor.images'),
        ]);
    }

    public function save(Request $request)
    {
        $dto = new SavePageDTO(
            title: $request->input('title', 'Sin título'),
            elements: $request->input('elements', []),
            slug: $request->input('slug', 'page-' . uniqid()),
        );

        $page = $this->savePageAction->execute($dto);

        return response()->json([
            'message' => 'Página guardada correctamente',
            'page' => [
                'id' => $page->getId(),
                'title' => $page->getTitle(),
                'slug' => $page->getSlug(),
                'elements' => $page->getElements(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $page = $this->getPageAction->bySlug($slug);

        if (!$page) {
            abort(404);
        }

        return Inertia::render('Pages/Views/Show', [
            'page' => [
                'id' => $page->getId(),
                'title' => $page->getTitle(),
                'elements' => $page->getElements(),
                'slug' => $page->getSlug(),
            ],
        ]);
    }
}
