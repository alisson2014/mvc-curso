<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;

class LoginController implements Controller
{
    use FlashMessageTrait;
    private \PDO $pdo;
    public function __construct()
    {
        $dbPath = __DIR__ . "/../../banco.sqlite";
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function processaRequisicao(): void
    {
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, "password");

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
        $verifyPassword = password_verify($password, $userData["password"] ?? "");

        if ($verifyPassword) {
            $_SESSION["isLoggedIn"] = true;
            header("Location: /");
        } else {
            $this->addErrorMessage("Usuário ou senha inválidos");
            header("Location: /login");
        }
    }
}
