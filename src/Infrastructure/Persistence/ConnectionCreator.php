<?php

declare(strict_types=1);

namespace Alura\Mvc\Infrastructure\Persistence;

use PDO;

final class ConnectionCreator
{
    private const DATA_BASE_PATH = __DIR__ . "/../../../banco.sqlite";

    /** @return PDO */
    public static function createConnection(): PDO
    {
        $connection = new PDO("sqlite:" . self::DATA_BASE_PATH);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
