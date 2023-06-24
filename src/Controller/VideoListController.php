<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\HtmlRedererTrait;
use Alura\Mvc\Repository\VideoRepository;

class VideoListController
{
    use HtmlRedererTrait;
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $videoList = $this->videoRepository->all();
        echo $this->renderTemplate("video-list", ["videoList" => $videoList]);
    }
}
