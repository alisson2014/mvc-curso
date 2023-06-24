<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class JsonVideoListController implements Controller
{

    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $videoList = array_map(function (Video $video): array {
            $imgFilePath = $video->getFilePath() ? "/img/uploads/{$video->getFilePath()}" : null;
            return [
                "url" => $video->url,
                "title" => $video->title,
                "file_path" => $imgFilePath,
            ];
        }, $this->videoRepository->all());
        echo json_encode($videoList);
    }
}
