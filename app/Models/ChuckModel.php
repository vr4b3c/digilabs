<?php

namespace App\Models;

use Nette;
use Nette\Utils\DateTime;

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
        $this->JOKES  = $this->fetchJokes();
        $this->IMAGE  = $this->getImage();           
    }

    
    private function fetchJokes() 
    {
        $jokesJson = file_get_contents($this->config["jokesUrl"]);
        return json_decode($jokesJson, true);
    } 
    
    private function getImage() 
    {
        return $this->config["imageUrl"];
    } 
       

    /**
     * Vrati pouze vtipy jehoz autor ma stejne inicialy
     * return array
     */
    public function getJokesByInitials()
    {
        $jokesWithSameInitials = array_filter($this->JOKES, function($item) {
            $nameParts = explode(' ', $item['name']);

            $initials = [];
            foreach ($nameParts as $namePart) {
                $initials[] = substr($namePart, 0, 1);
            }
            $initials = array_unique($initials);

            if (count($initials) > 1) {
                return false;
            }
            return true; 
        });

        return $jokesWithSameInitials;
    }

    public function getJokesByValid() 
    {
        $validJokes = array_filter($this->JOKES, function($joke) {
            if ($joke['firstNumber'] % 2 != 0) { // vyloucile liche firstNumber
                return false;
            }

            if (!$joke['firstNumber'] OR !$joke['secondNumber']) { return false; } // osetreni deleni nulou
            if (($joke['firstNumber'] / $joke['secondNumber']) == $joke['thirdNumber']) {
                return true;
            }
            return false;
        });

        return $validJokes;      
    } 
 
    public function getRandomByTime() 
    {
        $now = new DateTime();

        $jokes = array_filter($this->JOKES, function($joke) use ($now) {
            $createdAt = new DateTime($joke['createdAt']);
            return $createdAt >= $now->modify('-1 month') && $createdAt <= $now->modify('+1 month');
        });       

        return $jokes;    
    } 

    public function getJokesByCalc() 
    {
        $jokes = array_filter($this->JOKES, function($joke) {
            return $this->isCalculationCorrect($joke['calculation']);
        });       

        return $jokes;        
    } 


    function isCalculationCorrect($calculation) {

        list($left, $right) = explode('=', $calculation);

        $left  = trim($left);
        $right = trim($right);

        $leftResult  = $this->evaluateExpression($left);
        $rightResult = $this->evaluateExpression($right);
    
        return $leftResult === $rightResult;
    }
    
    
    function evaluateExpression($expression) {

        $operators = ['+', '-', '*', '/'];
    
        $tokens = preg_split('~\s*([+\-*/])\s*~', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    
        $result = 0;
        $currentOperator = '+';
    
        foreach ($tokens as $token) {
            if (in_array($token, $operators)) {
                $currentOperator = $token;
            } else {
                $number = (float)$token;
    
                switch ($currentOperator) {
                    case '+':
                        $result += $number;
                        break;
                    case '-':
                        $result -= $number;
                        break;
                    case '*':
                        $result *= $number;
                        break;
                    case '/':
                        if ($number == 0) {
                            throw new Exception("Division by zero");
                        }
                        $result /= $number;
                        break;
                }
            }
        }
    
        return $result;
    }
     

    /**
     * Vrátí náhodný vtip, jehož délka nepřesahuje 120 znaků.
     * @return array
     */
    public function getRandomJoke() 
    {
        
        $jokes = array_filter($this->JOKES, function($item) {
            return strlen($item['joke']) <= 120;
        });

        $randomJoke = $jokes[array_rand($jokes)];
        $randomJoke = $this->splitJoke($randomJoke);
        
        return $randomJoke;
    } 

    /**
     * Rozdeli vtip na dve pulky po celych slovech co nejblize stredu
     */
    private function splitJoke($joke)
    {
        $text = $joke['joke'];

        $middle = floor(strlen($text) / 2);
        $left   = strrpos(substr($text, 0, $middle), ' ');
        $right  = strpos($text, ' ', $middle);

        $left = $left ?: 0;
        $right = $right ?: strlen($text);

        if (($middle - $left) <= ($right - $middle)) {
            $splitPos = $left;
        } else {
            $splitPos = $right;
        }

        $joke["joke1"] = substr($text, 0, $splitPos);
        $joke["joke2"] = substr($text, $splitPos + 1);

        return $joke;
    }
}

