<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\User;
use Alura\Mvc\Helper\TryAction;
use PDO;

final class UserRepository
{
    use TryAction;
    public function __construct(private PDO $pdo)
    {
    }

    public function add(User $user): bool
    {
        $this->pdo->beginTransaction();

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $user->getEmail());
        $stmt->bindValue(2, $user->getPassword());
        $result = $this->tryAction($stmt);

        return $result["result"];
    }

    public function update(User $user): bool
    {
        return true;
    }

    public function remove(string $email): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result["result"];
    }

    public function find(string $email): User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?;");
        $stmt->bindValue(1, $email);
        $stmt->execute();

        return $this->hydrateUser($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function all(): array
    {
        $userList = $this->pdo
            ->query("SELECT * FROM users")
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            $this->hydrateUser(...),
            $userList
        );
    }

    private function hydrateUser(array $userData): User
    {
        $user = new User($userData["email"], $userData["password"]);
        return $user;
    }
}
