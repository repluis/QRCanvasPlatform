<?php

namespace Src\Pages\Domain\Repositories;

use Src\Pages\Domain\Entities\Page;

interface PageRepositoryInterface
{
    public function save(Page $page): Page;

    public function findByUuid(string $uuid): ?Page;

    public function findById(int $id): ?Page;

    /** @return Page[] */
    public function findByUserId(int $userId): array;
}
