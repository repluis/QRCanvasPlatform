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
        $page = new Page(
            title: $dto->title,
            elements: $dto->elements,
            slug: $dto->slug,
        );

        return $this->pageRepository->save($page);
    }
}
