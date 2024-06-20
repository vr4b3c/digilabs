<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
                

        // CMS 
            // zakladni akce
                $router->addRoute('admin/logout', [
                    'module' => 'Backend',
                    'presenter' => 'Login',
                    'action' => 'logout'   
                ]);  
                
                $router->addRoute('admin/login', [
                    'module' => 'Backend',
                    'presenter' => 'Login',
                    'action' => 'login'   
                ]);                  

            // staticke moduly
                $router->addRoute('admin/dashboard', [
                    'module' => 'Backend',
                    'presenter' => 'Dashboard',
                    'action' => 'view',
                ]); 

            // dynamicke moduly
                $router->addRoute('admin/<modul>/[<action>/][<id>/]', [
                    //  'locale' => 'cs',
                      'module' => 'Backend',
                      'presenter' => 'Core',
                      'action' => 'view',
                      'modul' => 'pages',
                      'id' => NULL           
                  ]);    

                  
            // FRONTEND 
                $router->addRoute('robots.txt', [
                    'module' => 'Frontend',
                    'presenter' => 'Web',
                    'action' => 'robots',
                    'page' => NULL
                ]); 
                $router->addRoute('sitemap.xml', [
                    'module' => 'Frontend',
                    'presenter' => 'Web',
                    'action' => 'sitemap',
                    'page' => NULL
                ]);    
                $router->addRoute('manifest.json', [
                    'module' => 'Frontend',
                    'presenter' => 'Web',
                    'action' => 'manifest',
                    'page' => NULL
                ]);          
                /*         
                $router->addRoute('favicon.ico', [
                    'module' => 'Frontend',
                    'presenter' => 'Web',
                    'action' => 'favicon',
                    'page' => NULL
                ]);     */              
                              
                
                $router->addRoute('[<page>/]', [
                    'module' => 'Frontend',
                    'presenter' => 'Web',
                    'action' => 'default',
                    'page' => NULL
                ]);                  
                
                
  

		return $router;
	}
}
