<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Helper\ValidateId;
use Alura\Mvc\Repository\VideoRepository;

class EditVideoController implements Controller
{
    use FlashMessageTrait, ValidateId;
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if (!$this->validateId($id)) {
            $this->addErrorMessage("Id inválido");
            header("Location: /editar-video");
            return;
        }

        $url = filter_input(INPUT_POST, "url", FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, "titulo");

        if (!$url || !$titulo) {
            $this->addErrorMessage("URL inválida");
            header("Location: /editar-video");
            return;
        }

        $video = new Video($url, $titulo);
        $video->setId($id);

        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES["image"]["tmp_name"]);

            if (str_starts_with($mimeType, "image/")) {
                $safeFileInfo = uniqid("upload_") . "_" . pathinfo($_FILES["image"]["tmp_name"], PATHINFO_BASENAME);
                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    __DIR__ . "/../../public/img/uploads/{$safeFileInfo}"
                );
                $video->setFilePath($safeFileInfo);
            }
        }

        $success = $this->videoRepository->update($video);

        if (!$success) {
            $this->addErrorMessage("Falha ao editar video");
            header("Location: /editar-video");
            return;
        }

        header("Location: /");
    }
}
