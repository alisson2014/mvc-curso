<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Infrastructure\Repository\VideoRepository;
use Alura\Mvc\Service\{FlashMessageTrait, ValidateId};
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class DeleteVideoController implements RequestHandlerInterface
{
    use ValidateId, FlashMessageTrait;
    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = $this->validateId($queryParams["id"]);

        $success = $this->videoRepository->remove($id);
        if (!$success) {
            $this->addErrorMessage("Erro ao remover video");
        }

        return new Response(302, ["Location" => "/"]);
    }
}
