<?php

declare(strict_types=1);

// CONSTANTS
define("PREFIX", "cms_");


require dirname(__DIR__) . '/app/functions.php';
require dirname(__DIR__) . '/vendor/autoload.php';

createRequiredFolders();

$configurator = App\Bootstrap::boot();
$container    = $configurator->createContainer();
$application  = $container->getByType(Nette\Application\Application::class);
$application->run();
