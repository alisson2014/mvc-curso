<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Helper\ValidateId;
use Alura\Mvc\Repository\VideoRepository;

class DeleteVideoController implements Controller
{
    use ValidateId, FlashMessageTrait;
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        $this->validateId($id);

        $success = $this->videoRepository->remove($id);
        if (!$success) {
            $this->addErrorMessage("Erro ao remover video");
            header("Location: /remover-video");
            return;
        }

        header("Location: /");
    }
}
