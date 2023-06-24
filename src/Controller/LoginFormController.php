<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

class LoginFormController extends ControllerWithHtml
{
    public function processaRequisicao(): void
    {
        if (array_key_exists("isLoggedIn", $_SESSION) && $_SESSION["isLoggedIn"]) {
            header("Location: /");
            return;
        }

        echo $this->renderTemplate("login-form");
    }
}
