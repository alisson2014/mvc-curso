<?php

declare(strict_types=1);

namespace Alura\Mvc\Domain\Repository;

use Alura\Mvc\Domain\Model\Video;

interface VideoRepo extends Repository
{
    public function add(Video $Video): bool;
    public function update(Video $user): bool;
    public function remove(int $id): bool;
    public function find(int $id): Video;
}
