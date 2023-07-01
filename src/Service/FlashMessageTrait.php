<?php

declare(strict_types=1);

namespace Alura\Mvc\Service;

trait FlashMessageTrait
{
    /**
     * @param string $errorMessage
     * @return void
     */
    private function addErrorMessage(string $errorMessage): void
    {
        $_SESSION["error_message"] = $errorMessage;
    }
}
