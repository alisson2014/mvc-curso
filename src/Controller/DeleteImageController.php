<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\{FlashMessageTrait, ValidateId};
use Alura\Mvc\Repository\VideoRepository;
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
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);
        $this->validateId($id);

        $success = $this->videoRepository->removeImage($id);
        if (!$success) {
            $this->addErrorMessage("Erro ao remover imagem");
            return new Response(302, ["Location" => "/remover-imagem"]);
        }

        return new Response(302, ["Location" => "/"]);
    }
}
