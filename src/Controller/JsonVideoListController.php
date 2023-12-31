<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Domain\Model\Video;
use Alura\Mvc\Infrastructure\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class JsonVideoListController implements RequestHandlerInterface
{

    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $videoList = array_map(function (Video $video): array {
            $imgFilePath = $video->getFilePath() ? "/img/uploads/{$video->getFilePath()}" : null;
            return [
                "url" => $video->url,
                "title" => $video->title,
                "file_path" => $imgFilePath,
            ];
        }, $this->videoRepository->all());
        return new Response(200, ["Content-Type" => "application/json"], json_encode($videoList));
    }
}
