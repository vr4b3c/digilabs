<?php

namespace App\Models;

use Nette;
use Nette\Security\Passwords;

class WebModel
{

    /** @var Nette\Database\Context */
    public $database;

    public $config; 
    public $locale;    
    public $_locale;
    public $DB_TABLE;
    
   // public $MODUL;

    /** @var Nette\Localization\ITranslator */
    private $translator;

    
    public function __construct ($config, Nette\Database\Context $database, Nette\Localization\ITranslator $translator) 
    {
        $this->database   = $database;
        $this->config     = $config;
        $this->translator = $translator;
 

     //   bdump($this->config);

    }

    public function getMenu ()
    {

        $items = $this->database->table($this->table('pages'))
                      ->where([
                            'visible' => '1',                     
                            'deleted' => '0',
                            'type'    => 'normal'
                        ])
                      ->order('id ASC')
                      ->fetchAssoc('tag');      
     //   bdump($items);
        return $items;

    }

    public function getFotogalery ()
    {
        $items = $this->database->table($this->table('fotogalery'))
                      ->where([
                            'visible' => '1',
                            'deleted' => '0'
                        ])
                      ->order('order ASC')
                      ->fetchAssoc('id');

        $items = $this->expandImages($items, "image");
//bdump($items);
        return $items;
    }

    public function expandImages ($items, $key = "image")
    {
        $output = [];
        foreach ($items AS $id => $item) {

            $images = json_decode($item[$key], true);
            $images = flattenArray($images);   
            $output[$id] = $item;
            $output[$id][$key] = $images;

        }
        return $output;     
    }

    public function getContacts ()
    {
        $items = $this->database->table($this->table('contacts'))
                      ->where([
                            'deleted' => '0',
                            'parent'  => '0'   
                        ])
                      ->order('id ASC')
                      ->fetchAssoc('tag');

        foreach ($items AS $key => $item) {
            $items[$key]['items'] = $this->database->table($this->table('contacts'))
            ->where([
                'deleted' => '0',
                'parent'  => $item['id']
            ])
            ->order('id ASC')
            ->fetchAssoc('tag');
        }

      // bdump($items);
        return $items;
    }

    public function getSettings ()
    {
        $items = $this->database->table($this->table('settings'))
                      ->order('id ASC')
                      ->fetchAssoc('key=value');

     //   bdump($items);
        return $items;
    }

    public function table($modul) 
    {
        return PREFIX.$modul;
    }


 
    public function saveContactForm ($data)
    {
        $items = $this->database->table($this->table('contactform'))->insert($data);
 
    }
     
}

