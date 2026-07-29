<?php

namespace Src\Pages\Application\Actions;

use Src\Pages\Domain\Repositories\PageRepositoryInterface;

class GetUserPagesAction
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository
    ) {}

    public function execute(int $userId): array
    {
        return $this->pageRepository->findByUserId($userId);
    }
}
