<?php

namespace Src\Pages\Infrastructure\Persistence\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Pages\Domain\Entities\Page;
use Src\Shared\Domain\Concerns\HasUuid;

class PageModel extends Model
{
    use HasUuid;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'elements',
        'canvases',
        'background',
        'slug',
        'uuid',
        'user_id',
    ];

    protected $casts = [
        'elements' => 'array',
        'canvases' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toEntity(): Page
    {
        return new Page(
            id: $this->id,
            title: $this->title,
            elements: $this->elements ?? [],
            canvases: $this->canvases ?? null,
            background: $this->background ?? '#ffffff',
            slug: $this->slug,
            uuid: $this->uuid,
            userId: $this->user_id,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
        );
    }

    public static function fromEntity(Page $page): self
    {
        $model = $page->getId()
            ? static::query()->find($page->getId()) ?? new self()
            : new self();

        $model->title = $page->getTitle();
        $model->elements = $page->getElements();
        $model->canvases = $page->hasCanvases() ? $page->getCanvases() : null;
        $model->background = $page->getBackground();
        $model->slug = $page->getSlug();
        $model->uuid = $page->getUuid();
        $model->user_id = $page->getUserId();

        return $model;
    }
}
