<?php

namespace Src\Pages\Application\Actions;

use Src\Pages\Application\DTO\SavePageDTO;
use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;

class SavePageAction
{
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository
    ) {}

    public function execute(SavePageDTO $dto): Page
    {
        $existing = null;
        if ($dto->id) {
            $existing = $this->pageRepository->findById($dto->id);
        }

        if ($existing) {
            $page = new Page(
                id: $existing->getId(),
                title: $dto->title,
                elements: $dto->elements,
                slug: $dto->slug,
                userId: $dto->userId ?? $existing->getUserId(),
            );
        } else {
            $page = new Page(
                title: $dto->title,
                elements: $dto->elements,
                slug: $dto->slug,
                userId: $dto->userId,
            );
        }

        return $this->pageRepository->save($page);
    }
}
