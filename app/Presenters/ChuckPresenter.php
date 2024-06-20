<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\ChuckModel;


final class ChuckPresenter extends Nette\Application\UI\Presenter
{
    /** @var \App\Models\ChuckModel */
    public $ChuckModel;


    public function __construct(ChuckModel $ChuckModel)
    {
        $this->ChuckModel = $ChuckModel;
    }

    public function startup()
    {
        parent::startup();

        $this->template->listTitle  = '';
        $this->template->jokes      = [];    
        $this->template->randomJoke = [];
        $this->template->chuckImage = $this->ChuckModel->IMAGE;
    }

    public function renderDefault()
    {
     
    }

    public function handleRandomJoke()
    {
        $this->template->randomJoke = $this->ChuckModel->getRandomJoke();
    }

    public function handleJokesByInitials()
    {
        $this->template->listTitle = 'Jokes by authors with same initials';  
        $this->template->jokes = $this->ChuckModel->getJokesByInitials();
    }

    public function handleJokesByValid(): void
    {
        $this->template->listTitle = 'Jokes with valid calculation';
        $this->template->jokes = $this->ChuckModel->getJokesByValid();
    }

    public function handleJokesByTime(): void
    {
        $this->template->listTitle = 'Jokes +/- month by now';    
        $this->template->jokes = $this->ChuckModel->getRandomByTime();
    }

    public function handleJokesByCalc(): void
    {
        $this->template->listTitle = 'Jokes with valid calculation (challenge)';    
        $this->template->jokes = $this->ChuckModel->getJokesByCalc();
    }

}
