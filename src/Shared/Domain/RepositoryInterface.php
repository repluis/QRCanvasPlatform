<?php

namespace Src\Shared\Domain;

interface RepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Entity;

    public function save(Entity $entity): Entity;

    public function delete(int $id): void;
}
