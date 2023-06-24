<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

trait HtmlRedererTrait
{
    /**
     * @param string $templateName
     * @param ?array $context
     * @return string
     */
    private function renderTemplate(
        string $templateName,
        array $context = []
    ): string {
        $template_path =  __DIR__ . "/../../views/";
        extract($context);
        $fileRender = $template_path . $templateName . ".php";
        ob_start();
        require_once($fileRender);
        return ob_get_clean();
    }
}
