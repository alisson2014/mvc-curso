<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait TryAction
{
    private function tryAction(
        \PDOStatement|bool $stmt,
        bool $hasId = false,
    ): array {
        try {
            $result = $stmt->execute();

            if ($hasId) {
                $lastId = $this->pdo->lastInsertId();
            }

            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return [
            "result" => $result,
            "lastId" => $lastId
        ];
    }
}
