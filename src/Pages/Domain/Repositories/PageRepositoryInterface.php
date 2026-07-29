<?php

namespace Src\Pages\Domain\Repositories;

use Src\Pages\Domain\Entities\Page;

interface PageRepositoryInterface
{
    public function save(Page $page): Page;

    public function findBySlug(string $slug): ?Page;

    public function findById(int $id): ?Page;
}
