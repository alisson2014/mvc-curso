<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Domain\Model\Video;
use Alura\Mvc\Infrastructure\Repository\VideoRepository;
use Alura\Mvc\Service\{FlashMessageTrait, ValidateId};
use Nyholm\Psr7\Response;
use Psr\Http\Message\{
    ResponseInterface,
    ServerRequestInterface,
    UploadedFileInterface
};
use Psr\Http\Server\RequestHandlerInterface;

class EditVideoController implements RequestHandlerInterface
{
    use FlashMessageTrait, ValidateId;
    public function __construct(
        private VideoRepository $videoRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);
        $this->validateId($id);

        $url = filter_input(INPUT_POST, "url", FILTER_VALIDATE_URL);
        $titulo = filter_input(INPUT_POST, "titulo");

        if (!$url || !$titulo) {
            $this->addErrorMessage("URL inválida");
            return new Response(302, ["Location" => "/editar-video"]);
        }

        $video = new Video($url, $titulo);
        $video->setId($id);
        $files = $request->getUploadedFiles();
        /** @var UploadedFileInterface $uploadedImage */
        $uploadedImage = $files["image"];

        if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $tmpFile = $uploadedImage->getStream()->getMetadata("uri");
            $mimeType = $finfo->file($tmpFile);

            if (str_starts_with($mimeType, "image/")) {
                $safeFileInfo = uniqid("upload_") . "_" . pathinfo($_FILES["image"]["tmp_name"], PATHINFO_BASENAME);
                $uploadedImage->moveTo(__DIR__ . "/../../public/img/uploads/{$safeFileInfo}");
                $video->setFilePath($safeFileInfo);
            }
        }

        $success = $this->videoRepository->update($video);

        if (!$success) {
            $this->addErrorMessage("Falha ao editar video");
            return new Response(302, ["Location" => "/editar-video"]);
        }

        return new Response(302, ["Location" => "/"]);
    }
}
