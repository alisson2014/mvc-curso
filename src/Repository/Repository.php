<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

interface Repository
{
    public function all(): array;

    public function add($entity): bool;

    public function remove(int $id): bool;

    public function update($entity): bool;

    public function find(int $id): object;
}
