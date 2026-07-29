<?php

namespace Src\Pages\Application\Actions;

use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;

class GetPageAction
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository
    ) {}

    public function bySlug(string $slug): ?Page
    {
        return $this->pageRepository->findBySlug($slug);
    }

    public function byId(int $id): ?Page
    {
        return $this->pageRepository->findById($id);
    }
}
