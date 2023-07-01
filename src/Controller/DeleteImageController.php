<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Infrastructure\Repository\VideoRepository;
use Alura\Mvc\Service\{FlashMessageTrait, ValidateId};
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class DeleteImageController implements RequestHandlerInterface
{
    use ValidateId, FlashMessageTrait;
    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        var_dump($queryParams);
        $id = $this->validateId($queryParams["id"]);

        $success = $this->videoRepository->removeImage($id);
        if (!$success) {
            $this->addErrorMessage("Erro ao remover imagem");
            return new Response(302, ["Location" => "/remover-imagem"]);
        }

        return new Response(302, ["Location" => "/"]);
    }
}
