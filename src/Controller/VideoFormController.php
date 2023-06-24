<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class VideoFormController extends ControllerWithHtml
{
    use ValidateId;
    public function __construct(private VideoRepository $repository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        /** @var ?Video $video */
        $video = null;
        if ($this->validateId($id)) {
            $video = $this->repository->find($id);
        }

        echo $this->renderTemplate("video-form", ["video" => $video]);
    }
}
