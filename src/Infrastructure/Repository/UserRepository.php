<?php

declare(strict_types=1);

namespace Alura\Mvc\Infrastructure\Repository;

use Alura\Mvc\Domain\Model\User;
use Alura\Mvc\Domain\Repository\UserRepo;
use Alura\Mvc\Service\TryAction;
use PDO;

final class UserRepository implements UserRepo
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

    public function find(string $email): User|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?;");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $data = $stmt->fetch() ?: [];

        if (count($data) > 0) {
            return $this->hydrateUser($data);
        }

        return false;
    }

    public function all(): array
    {
        $userList = $this->pdo
            ->query("SELECT * FROM users")
            ->fetchAll();

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
