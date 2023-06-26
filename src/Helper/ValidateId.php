<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait ValidateId
{
    /**
     * @param int|false|null $id
     * @return ResponseInterface
     */
    private function validateId(int|false|null $id): ResponseInterface
    {
        if (!$id) {
            $_SESSION["error_message"] = "Id inválido!";
            return new Response(302, ["Location" => "/"]);
        }

        return new Response();
    }
}
