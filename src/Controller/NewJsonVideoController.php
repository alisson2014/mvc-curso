<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Domain\Model\Video;
use Alura\Mvc\Infrastructure\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class NewJsonVideoController implements RequestHandlerInterface
{
    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestContent = $request->getBody()->getContents();
        $videoData = json_decode($requestContent, true);
        $video = new Video($videoData["url"], $videoData["title"]);
        $this->videoRepository->add($video);

        return new Response(201);
    }
}
