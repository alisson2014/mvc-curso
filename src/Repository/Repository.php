<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

interface Repository
{
    public function all(): array;

    public function add(object $entity): bool;

    public function remove(int $id): bool;

    public function update(object $entity): bool;

    public function find(int $id): object;
}
