<?php

declare(strict_types=1);

namespace Alura\Mvc\Entity;

readonly class User
{
    public function __construct(
        private string $email,
        private string $password
    ) {
    }

    private function getEmail(): string
    {
        return $this->email;
    }

    private function getPassword(): string
    {
        return $this->password;
    }

    public function __get(string $method): string
    {
        $method .= "get" . ucfirst($method);
        return $this->$method();
    }
}
