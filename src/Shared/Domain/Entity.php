<?php

namespace Src\Shared\Domain;

abstract class Entity
{
    protected function __construct(
        protected readonly ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function equals(Entity $other): bool
    {
        if ($this->id === null || $other->id === null) {
            return $this === $other;
        }

        return $this->id === $other->id;
    }
}
