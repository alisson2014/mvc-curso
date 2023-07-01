<?php

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => \Alura\Mvc\Infrastructure\Persistence\ConnectionCreator::createConnection(),
    \League\Plates\Engine::class => function () {
        $templatePath = __DIR__ . "/../views";
        return new \League\Plates\Engine($templatePath);
    }
]);

/** @var \Psr\Container\ContainerInterface $container */
$container = $builder->build();

return $container;
