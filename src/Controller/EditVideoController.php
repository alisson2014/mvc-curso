<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class EditVideoController implements Controller
{
    use ValidateId;
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if (!$this->validateId($id)) {
            header("Location: /?sucesso=0");
            return;
        }

        $url = filter_input(INPUT_POST, "url", FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, "titulo");

        if ($url === false || $titulo === false) {
            header("Location: /?success=0");
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

        if ($success === false) {
            header("Location: /?success=0");
            return;
        }

        header("Location: /?success=1");
    }
}
