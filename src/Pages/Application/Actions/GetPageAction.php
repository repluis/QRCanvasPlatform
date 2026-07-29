<?php

namespace Src\Pages\Application\Actions;

use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;

class GetPageAction
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository
    ) {}

    public function byUuid(string $uuid): ?Page
    {
        return $this->pageRepository->findByUuid($uuid);
    }

    public function byId(int $id): ?Page
    {
        return $this->pageRepository->findById($id);
    }
}
