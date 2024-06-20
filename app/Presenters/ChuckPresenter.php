<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\ChuckModel;


final class ChuckPresenter extends Nette\Application\UI\Presenter
{
    /** @var \App\Models\ChuckModel */
    public $ChuckModel;    


  

    public function __construct (ChuckModel $ChuckModel) {  

        $this->ChuckModel = $ChuckModel; 
   
    }

    public function startup() 
    {
        parent::startup();

    }  

    public function renderDefault() 
    {

       // $this->template-

    }

    
    public function handleGetRandomJoke()
    {
        
        
        $this->template->randomJoke = $this->ChuckModel->getRandomJoke();
        bdump($this->template->randomJoke);
        
        $this->redrawControl('randomJoke');
    }
    
    
}
