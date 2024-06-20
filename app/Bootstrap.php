<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{
            $configurator = new Configurator;
            $root         = dirname(__DIR__);

            $configurator->setTempDirectory($root.'/temp');
            $configurator->enableTracy($root.'/log');   
            $configurator->setDebugMode(false);

            $configurator->createRobotLoader()
                         ->addDirectory(__DIR__)
                         ->register();

            $configurator->addConfig($root.'/config/config.neon');      

            return $configurator;
	}
}


