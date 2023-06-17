<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\Video;
use PDO;

class VideoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Video $video): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO videos (url, title) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $video->url, PDO::PARAM_STR);
        $stmt->bindValue(2, $video->title, PDO::PARAM_STR);

        try {
            $result = $stmt->execute();

            $id = $this->pdo->lastInsertId();
            $video->setId(intval($id));

            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return $result;
    }

    public function remove(int $id): bool
    {
        $result = false;

        $this->pdo->beginTransaction();
        $sql = "DELETE FROM videos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return $result;
    }

    public function update(Video $video): bool
    {
        $result = false;

        $this->pdo->beginTransaction();
        $sql = "UPDATE videos SET url = :url, title = :title WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":url", $video->url, PDO::PARAM_STR);
        $stmt->bindValue(":title", $video->title, PDO::PARAM_STR);
        $stmt->bindValue(":id", $video->id, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return $result;
    }

    /**
     * @return Video[]
     */
    public function all(): array
    {
        $videoList = $this->pdo
            ->query("SELECT * FROM videos;")
            ->fetchAll(PDO::FETCH_ASSOC);
        return array_map(
            $this->hydrateVideo(...),
            $videoList
        );
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateVideo($stmt->fetch(PDO::FETCH_ASSOC));
    }

    private function hydrateVideo(array $videoData): Video
    {
        $video = new Video($videoData["url"], $videoData["title"]);
        $video->setId($videoData["id"]);

        return $video;
    }
}
