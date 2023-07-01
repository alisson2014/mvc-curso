<?php

declare(strict_types=1);

namespace Alura\Mvc\Domain\Repository;

use Alura\Mvc\Domain\Model\User;

interface UserRepo extends Repository
{
    public function add(User $user): bool;
    public function update(User $user): bool;
    public function remove(string $email): bool;
    public function find(string $email): User|false;
}
