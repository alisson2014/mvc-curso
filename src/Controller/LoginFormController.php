<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

class LoginFormController implements Controller
{
    public function processaRequisicao(): void
    {
        if (array_key_exists("isLoggedIn", $_SESSION) && $_SESSION["isLoggedIn"] === true) {
            header("Location: /");
            return;
        }
        require_once __DIR__ . "/../../views/login-form.php";
    }
}
