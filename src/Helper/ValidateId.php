<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait ValidateId
{
    /**
     * @param int|false|null $id
     * @return bool
     */
    public function validateId(int|false|null $id): bool
    {
        return ($id !== null && $id !== false);
    }
}
