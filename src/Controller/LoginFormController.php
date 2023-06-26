<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\HtmlRedererTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginFormController implements RequestHandlerInterface
{
    use HtmlRedererTrait;
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (array_key_exists("isLoggedIn", $_SESSION) && $_SESSION["isLoggedIn"]) {
            return new Response(302, ["Location" => "/"]);
        }

        return new Response(200, body: $this->renderTemplate("login-form"));
    }
}
