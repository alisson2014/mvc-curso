<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;
    private \PDO $pdo;
    public function __construct()
    {
        $dbPath = __DIR__ . "/../../banco.sqlite";
        $this->pdo = new \PDO("sqlite:$dbPath");
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
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
            return new Response(302, ["Location" => "/"]);
        }

        $this->addErrorMessage("Usuário ou senha inválidos");
        return new Response(302, ["Location" => "/login"]);
    }
}
