<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class NewVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait;
    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $url = filter_input(INPUT_POST, "url", FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, "titulo");

        if (!$url || !$titulo) {
            $this->addErrorMessage("URL inválida");
            return new Response(302, ["Location" => "/novo-video"]);
        }

        $video = new Video($url, $titulo);
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

        $success = $this->videoRepository->add($video);
        if (!$success) {
            $this->addErrorMessage("Erro ao cadastrar vídeo");
            return new Response(302, ["Location" => "/novo-video"]);
        }

        return new Response(200, ["Location" => "/"]);
    }
}
