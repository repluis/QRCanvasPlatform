<?php

namespace Src\Pages\Domain\Entities;

use Src\Shared\Domain\Entity;

class Page extends Entity
{
    public function __construct(
        ?int $id = null,
        private string $title = 'Sin título',
        private array $elements = [],
        private ?string $slug = null,
        private ?string $uuid = null,
        private ?int $userId = null,
        private mixed $createdAt = null,
        private mixed $updatedAt = null,
    ) {
        parent::__construct($id);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getSlug(): string
    {
        return $this->slug ?? 'page-' . $this->getId();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getCreatedAt(): mixed
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): mixed
    {
        return $this->updatedAt;
    }
}
