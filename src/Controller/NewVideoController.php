<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $url = filter_input(INPUT_POST, "url", FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, "titulo");

        if ($url === false || $titulo === false) {
            header("Location: /?success=0");
            return;
        }

        $video = new Video($url, $titulo);
        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                __DIR__ . "/../../public/img/uploads/" . $_FILES["image"]["name"]
            );
            $video->setFilePath($_FILES["image"]["name"]);
        }

        $success = $this->videoRepository->add($video);
        if ($success === false) {
            header("Location: /?success=0");
            return;
        }

        http_response_code(201);
        echo "
            <script>
                window.setTimeout(function() { 
                    window.location.href = '/?success=1'; 
                }, 500);
            </script>
        ";
    }
}
