<?php

declare(strict_types=1);

namespace App\Frontend\Presenters;

use Nette;
use App\Models\WebModel;
use App\Models\EmailSender;

final class WebPresenter extends Nette\Application\UI\Presenter
{
    /** @var \App\Models\WebModel */
    public $WebModel;    

    /** @var \App\Models\EmailSender */
    public $EmailSender; 
    

    public function __construct (
        WebModel $WebModel,
        EmailSender $EmailSender
    ) {  

        $this->WebModel    = $WebModel; 
        $this->EmailSender = $EmailSender;     

    }

    public function startup() 
    {
        parent::startup();


        $this->template->loggedUser = $loggedUser = $this->getUser()->getIdentity();
        $this->template->admin_logged = in_array($loggedUser->roles[0]??[], ['admin','superadmin']);
    }  

    public function renderDefault($page = '') 
    {

        $this->template->menu     = $this->WebModel->getMenu();
        $this->template->contacts = $this->WebModel->getContacts();  
        $this->template->settings = $this->WebModel->getSettings();   
        $this->template->galery   = $this->WebModel->getFotogalery();   

     //   $this->template->open     = $this->isOpenhours($this->template->settings);

    }

    public function handleContactform()
    {
        $rawData = file_get_contents('php://input');
        $data    = json_decode($rawData, true);

        if (!empty($data)) {

            $data["date"] = date("Y-m-d H:i:s");
            $message = 'Z kontaktního formuláře na webu byla zaslána nová zpráva:<br><hr>'.$data["message"].'<br><br>'.$data["name"].'<br>'.$data["email"];

            $this->WebModel->saveContactForm($data);
            $this->EmailSender->send_email_to_admin('Zpráva z kontaktního formuláře', $message);

            $this->sendJson(['status' => '✓ Vaše zpráva byla úspěšně odeslána!']); 
        }     
    }
   
/*
    public function isOpenhours($settings)
    {

        $d  = date("w");
        $hm = date("Hi");

        $key = ($d == 0 || $d == 6) ? 'weekend' : 'week';

        $od = $settings["openhours_".$key."_from"] ?? '';
        $od = str_replace(":", "", $od); 

        $do = $settings["openhours_".$key."_to"] ?? '';
        $do = str_replace(":", "", $do);

        if ($hm >= $od && $hm <= $do) {
            return true;
        } else {
            return false;
        }
    }
*/
    
public function renderRobots()
{
    $httpResponse = $this->getHttpResponse();
    $httpResponse->setContentType('text/plain', 'UTF-8'); 
    
    $this->template->settings = $this->WebModel->getSettings();
}

public function renderSitemap()
{
    $httpResponse = $this->getHttpResponse();
    $httpResponse->setContentType('application/xml', 'UTF-8');     
    
    $this->template->menu     = $this->WebModel->getMenu();   
    $this->template->settings = $this->WebModel->getSettings();
} 

public function renderManifest()
{
    $this->template->settings = $this->WebModel->getSettings(); 
}

public function renderFavicon() 
{
    $this->template->path = $path = __DIR__.'/../../../www/favicon.ico';
    $this->template->favicon = file_get_contents($path); 
}
}
