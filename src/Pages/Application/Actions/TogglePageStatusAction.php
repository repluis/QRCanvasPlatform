<?php

namespace Src\Pages\Application\Actions;

use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;

class TogglePageStatusAction
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository
    ) {}

    public function execute(int $pageId, int $userId): Page
    {
        $page = $this->pageRepository->findById($pageId);

        if (!$page) {
            throw new \RuntimeException('Page not found');
        }

        if ($page->getUserId() !== $userId) {
            throw new \RuntimeException('Unauthorized');
        }

        return $this->pageRepository->toggleStatus($pageId, $userId);
    }
}
