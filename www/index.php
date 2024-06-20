<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$configurator = App\Bootstrap::boot();
$container    = $configurator->createContainer();
$application  = $container->getByType(Nette\Application\Application::class);
$application->run();
