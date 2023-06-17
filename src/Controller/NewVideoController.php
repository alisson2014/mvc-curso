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

        $success = $this->videoRepository->add(new Video($url, $titulo));
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
