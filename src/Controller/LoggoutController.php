<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

class LoggoutController implements Controller
{
    public function processaRequisicao(): void
    {
        unset($_SESSION["isLoggedIn"]);
        header("Location: /login");
    }
}
