<?php

declare(strict_types=1);

namespace Alura\Mvc\Infrastructure\Repository;

use Alura\Mvc\Domain\Model\Video;
use Alura\Mvc\Domain\Repository\VideoRepo;
use Alura\Mvc\Service\TryAction;
use PDO;

final class VideoRepository implements VideoRepo
{
    use TryAction;
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Video $video): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO videos (url, title, image_path) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $video->url);
        $stmt->bindValue(2, $video->title);
        $stmt->bindValue(3, $video->getFilePath());
        $result = $this->tryAction($stmt, true);
        $status = $result["result"];

        if ($status) {
            $video->setId(intval($result["lastId"]));
        }

        return $status;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM videos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result["result"];
    }

    public function removeImage(int $id): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE videos SET image_path = NULL WHERE id = ? AND image_path IS NOT NULL;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result["result"];
    }

    public function update(Video $video): bool
    {
        $this->pdo->beginTransaction();

        $updateImageSql = "";
        if ($video->getFilePath() !== null) {
            $updateImageSql = ", image_path = :image_path";
        }
        $sql = "UPDATE videos SET url = :url, title = :title $updateImageSql WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":url", $video->url);
        $stmt->bindValue(":title", $video->title);
        $stmt->bindValue(":id", $video->id, PDO::PARAM_INT);

        if ($video->getFilePath() !== null) {
            $stmt->bindValue(":image_path", $video->getFilePath());
        }

        $result = $this->tryAction($stmt);

        return $result["result"];
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

    public function find(int $id): Video
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

        if ($videoData["image_path"] !== null) {
            $video->setFilePath($videoData["image_path"]);
        }

        return $video;
    }
}
