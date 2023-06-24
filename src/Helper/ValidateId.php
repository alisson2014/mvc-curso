<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait ValidateId
{
    /**
     * @param int|false|null $id
     * @param ?string $toLocation
     * @return void
     */
    private function validateId(
        int|false|null $id,
        string|null $toLocation = null
    ): void {
        if (!$id) {
            $_SESSION["error_message"] = "Id inválido!";
            header("Location: /{$toLocation}");
            exit();
        }
    }
}
