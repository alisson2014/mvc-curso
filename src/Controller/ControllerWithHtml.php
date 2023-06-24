<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

abstract class ControllerWithHtml implements Controller
{
    private const TEMPLATE_PATH = __DIR__ . "/../../views/";

    /**
     * @param string $templateName
     * @param ?array $context
     * @return string
     */
    protected function renderTemplate(
        string $templateName,
        array $context = []
    ): string {
        extract($context);
        $fileRender = self::TEMPLATE_PATH . $templateName . ".php";
        ob_start();
        require_once($fileRender);
        return ob_get_clean();
    }
}
