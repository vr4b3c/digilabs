<?php

namespace App\Models;

use Nette;
use Nette\Security\Passwords;

class ChuckModel
{

    /** @var Nette\Database\Context */
    public $database;

    public $config; 
    
    
    public $JOKES;
    public $IMAGE;  

    
    public function __construct ($config) 
    {
        $this->config = $config;
        $this->JOKES  = $this->getJokes();
        $this->IMAGE  = $this->getImage();           
    }

    
    private function getJokes() 
    {
        $jokesJson = file_get_contents($this->config["jokesUrl"]);
        return json_decode($jokesJson, true);
    } 
    
    private function getImage() 
    {
        return $this->config["imageUrl"];
    } 
       

    /**
     * Vrátí náhodný vtip, jehož délka nepřesahuje 120 znaků.
     * @return arraz
     */
    public function getRandomJoke() 
    {
        
        $jokes = array_filter($this->JOKES, function($item) {
            return strlen($item['joke']) <= 120;
        });
        
        return $jokes[array_rand($jokes)];
    } 
}

