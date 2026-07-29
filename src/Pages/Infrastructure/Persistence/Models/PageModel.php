<?php

namespace Src\Pages\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Src\Pages\Domain\Entities\Page;

class PageModel extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'title',
        'elements',
        'slug',
    ];

    protected $casts = [
        'elements' => 'array',
    ];

    public function toEntity(): Page
    {
        return new Page(
            id: $this->id,
            title: $this->title,
            elements: $this->elements,
            slug: $this->slug,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
        );
    }

    public static function fromEntity(Page $page): self
    {
        $model = new self();
        if ($page->getId() !== null) {
            $model->id = $page->getId();
        }
        $model->title = $page->getTitle();
        $model->elements = $page->getElements();
        $model->slug = $page->getSlug();

        return $model;
    }
}
