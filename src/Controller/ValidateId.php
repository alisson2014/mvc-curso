<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

trait ValidateId
{
    /**
     * @param int|false|null $id
     * @return bool
     */
    public function validateId(int|false|null $id): bool
    {
        $isValid = $id !== null && $id !== false;
        if ($isValid) {
            return true;
        }

        return false;
    }
}
