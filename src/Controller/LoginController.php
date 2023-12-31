<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Domain\Model\User;
use Alura\Mvc\Service\FlashMessageTrait;
use Alura\Mvc\Infrastructure\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;
    public function __construct(
        private UserRepository $userRepository,
        private \PDO $pdo
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, "password");

        $userData = $this->userRepository->find($email);
        $verifyPassword = $userData instanceof User
            ? password_verify($password, $userData->getPassword() ?? "")
            : false;

        if ($verifyPassword) {
            $_SESSION["isLoggedIn"] = true;
            return new Response(302, ["Location" => "/"]);
        }

        $this->addErrorMessage("Usuário ou senha inválidos");
        return new Response(302, ["Location" => "/login"]);
    }
}
