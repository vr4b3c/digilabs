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
            $enviroment   = $configurator->detectDebugMode('nette-debug@193.165.97.33') ? 'local' : 'prod'; 

            $configurator->setTempDirectory($root.'/temp');
            $configurator->enableTracy($root.'/log');   

            $configurator->createRobotLoader()
                         ->addDirectory(__DIR__)
                         ->register();

            $configurator->addConfig($root.'/config/config.neon');  
            $configurator->addConfig($root."/config/config.$enviroment.neon");       

            return $configurator;
	}
}


