<?php

namespace Src\Pages\Infrastructure\Persistence\Repositories;

use Src\Pages\Domain\Entities\Page;
use Src\Pages\Domain\Repositories\PageRepositoryInterface;
use Src\Pages\Infrastructure\Persistence\Models\PageModel;

class PageRepository implements PageRepositoryInterface
{
    public function save(Page $page): Page
    {
        $model = PageModel::fromEntity($page);
        $model->save();

        return $model->toEntity();
    }

    public function findByUuid(string $uuid): ?Page
    {
        $model = PageModel::where('uuid', $uuid)->first();

        return $model?->toEntity();
    }

    public function findById(int $id): ?Page
    {
        $model = PageModel::find($id);

        return $model?->toEntity();
    }

    public function findByUserId(int $userId): array
    {
        return PageModel::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn (PageModel $model) => $model->toEntity())
            ->all();
    }
}
