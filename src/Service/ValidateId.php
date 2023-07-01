<?php

declare(strict_types=1);

namespace Alura\Mvc\Service;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait ValidateId
{
    /**
     * @param ?string $id
     * @return ResponseInterface|int
     */
    private function validateId(?string $id): ResponseInterface|int
    {
        $filterId = filter_var($id, FILTER_VALIDATE_INT);

        if (!$filterId) {
            $_SESSION["error_message"] = "Id inválido!";
            return new Response(302, ["Location" => "/"]);
        }

        return $filterId;
    }
}
