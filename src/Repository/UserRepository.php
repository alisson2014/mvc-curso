<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\User;
use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(User $user): bool
    {
        $this->pdo->beginTransaction();

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(2, $user->getPassword(), PDO::PARAM_STR);

        try {
            $result = $stmt->execute();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return $result;
    }

    public function update(User $user): bool
    {
        return true;
    }

    public function remove(int $email): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return $result;
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
