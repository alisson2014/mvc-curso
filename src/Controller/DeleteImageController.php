<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;

class DeleteImageController implements Controller
{
    use ValidateId;
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$this->validateId($id)) {
            header("Location: /?success=0");
            return;
        }

        $success = $this->videoRepository->removeImage($id);
        if ($success === false) {
            header("Location: /?success=0");
            return;
        }

        header("Location: /?success=1");
    }
}
