<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\ValidateId;
use Alura\Mvc\Repository\VideoRepository;
use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class VideoFormController implements RequestHandlerInterface
{
    use ValidateId;
    public function __construct(
        private VideoRepository $repository,
        private Engine $templates
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"] ?? "", FILTER_VALIDATE_INT);
        /** @var ?Video $video */
        $video = null;
        if ($id !== null && $id !== false) {
            $video = $this->repository->find($id);
        }

        return new Response(200, body: $this->templates->render(
            "video-form",
            ["video" => $video]
        ));
    }
}
