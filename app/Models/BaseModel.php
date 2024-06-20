<?php

namespace App\Models;

use Nette;
use Nette\Security\Passwords;

class BaseModel
{

    /** @var Nette\Database\Context */
    public $database;

    public $config; 
    public $locale;    
    public $_locale;
    public $DB_TABLE;
    
    public $MODUL;

    /** @var Nette\Localization\ITranslator */
    private $translator;

    
    public function __construct ($config, Nette\Database\Context $database, Nette\Localization\ITranslator $translator) 
    {
        $this->database   = $database;
        $this->config     = $config;
        $this->translator = $translator;
 
    }
    
    public function getConfig() {
        return $this->config;
    }

    
    // ZAKLADNI DB OPERACE NAD POLOZKAMA 
        
        /**
         * Vrati seznam NESMAZANYCH polozek
         * Umi i radit
         * @param array $where
         * @param bool|string $sort
         * @return type
         */
        public function getItems(array $where = [], $sort = false) 
        { 
            $row = $this->modulTable()
                        ->where($where);
            
            if ($this->MODUL['columns']['deleted']??false) {
                $row->where('deleted = ?', 0);              
            }

            if ($sort) {
                if ($sort === true) {
                    $sort = $this->getDefaultSorting();
                }           
                $row->order($sort);
            }
            return $row;
        }       
        
        /**
         * Vrati polozku dle id
         * @param int $id
         * @return type
         */
        public function getItem(int $id) 
        {
            return $this->modulTable()
                        ->where('id', $id);
        }

        
        /**
         * Vrati pocet polozek dle zadaneho where
         * @param array $where
         * @return type
         */
        public function getItemsCount (array $where = [])
        {
           
            $colName = array_key_first($this->MODUL['columns']);
            return $this->getItems($where)
                        ->select("count($colName) AS cnt")->fetch()->cnt ?? 0;
        }         
        
        
        /**
         * Vrati polozku dle id - jako array
         * @param int $id
         * @return type
         */
        public function fetchItem(int $id) 
        {
            return $this->getItem($id)
                        ->fetch();
        }  

        /**
         * Vrati polozku dle id - jako array
         * @param int $id
         * @return type
         */
        public function fetchItemArray(int $id) 
        {
            return $this->getItem($id)
                        ->fetchAssoc('id=');
        }          
        /**
         * Vlozi polozku do DB
         * @param array $array
         * @return type
         */
        public function insertItem(array $values) 
        {
            return $this->modulTable()
                        ->insert($values);
        }     
        
        
        /**
         * Upravi polozku v DB
         * @param int $id
         * @param array $array
         * @return type
         */
        public function updateItem(int $id, array $array = []) 
        {
            return $this->getItem($id)
                        ->update($array);
        }     

        
        /**
         * Hromadne upravy polozek
         * @param array $arr
         */
        public function updateItems(array $arr = [])
        {
            foreach ($arr AS $id => $params)
            {
                $this->updateItem($id, $params);         
            }
        }   
 
        
        
        public function get_autoinserts_array($MODUL = []) 
        {
            
            $id = 1;
            $output = [];
            if (!empty($MODUL["autoinsert"])) {
                foreach ($MODUL["autoinsert"] AS $autoinsert) {
                    $output[$id] = array_merge(['id' => $id], $autoinsert);
                    $id++;
                }
            }
          //  bdump($output);
            return $output;       
        }
        
        
        
        
    // ZPRACOVANI SLOUPCU
        public function getColumnValue_url($values) 
        {
            $_locale = '';
            if (!empty($values['localeDb'])) {
                $_locale = '_'.$values['localeDb'];
            }
            if (isset($values["url".$_locale])) {
                
                if (isset($values["tag"]) AND ($values["tag"]??'') == 'homepage') {
                    $values["url".$_locale] == '';
                } else {
                    $values['url'.$_locale] = $this->getValidItemUrl($values['url'.$_locale]?:$values['name'.$_locale], $values['id']?:null, $_locale);  
                }
            }

            return $values;
        }
        
        public function getColumnValue_tag($values) 
        {

            if (isset($this->MODUL["columns"]["tag"]) AND empty($values["tag"])) {  
                if (!empty($values["url"])) {
                    $values["tag"] = $values["url"];
                } else {
                    $values["tag"] = $this->MODUL["name"].'-'.rand(000,999);           
                }
            }

            return $values;
        }
        
    
    
        /**
         * Doplní povinná pole pro ostatní jazyky
         * @param type $values
         * @return type
         */
        public function getTranslatedColumnValues($values) 
        {

            if (empty($values['localeDb'])) { return $values; }
            
            $otherLocales = $this->config['locales'];
            $localeKey    = array_search($values['localeDb']??'en', $otherLocales);
            unset($otherLocales[$localeKey]);

            
            foreach ($values AS $colName => $value) {  

                if ($this->MODUL['columns'][$colName]['translated']??false) {


                    if ($this->MODUL['columns'][$colName]['notnull']??false OR $this->MODUL['columns'][$colName]['unique']??false) {
                        foreach ($otherLocales AS $locale) {
                            $colName_translated = str_replace('_'.$values['localeDb']??'en', '_'.$locale, $colName); 
                            $values[$colName_translated] = $value;
                        }   
                    }   
                }        
            }   
         
            return $values;    
        }
      
        
    // OSTATNI POMOCNE FUNKCE
       
        /**
         * Vrati pole modulu
         * @return array
         */
        public function getConfigNavigation() 
        {
            return $this->config["navigation"];   
        }
        
        
        /**
         * Provede SQL dotaz
         * @param string $sql
         * @return type
         */
        public function doSql(string $sql) 
        {
            if ($sql) {
                return $this->database->query($sql);
            }
            return false;
        }

        
        /**
         * Vrati zakladni volani DB tabulky modulu
         * @return type
         */
        public function modulTable() 
        {
            return $this->database->table($this->DB_TABLE);
        }  
        
        
    // POMOCNE PODFUNKCE
  
        /**
         * Vrati predchoziho sourozence dle poradi
         * @param type $row
         * @param array $where
         * @return type
         */
        public function get_prev_by_order($row, array $where = []) 
        {    

            if (isset($row->parent)) { // podmineno existenci sloupce kvuli sorting=simple
                $where['parent'] = $row->parent;
            }
            
            $row = $this->getItems($where)
                        ->where('order < ?', $row->order)
                        ->where('order != ?', 0)              
                        ->order('order DESC')
                        ->limit(1)
                        ->fetch();  
            
            return $row;
        }    

        
        /**
         * Vrati pristiho sourozence dle poradi
         * @param type $row
         * @param array $where
         * @return type
         */
        public function get_next_by_order($row, array $where = []) 
        {       
            if (isset($row->parent)) {
                $where['parent'] = $row->parent;
            }
            
            $row = $this->getItems($where)
                        ->where('order > ?', $row->order)
                        ->where('order != ?', 0)                   
                        ->order('order ASC')
                        ->limit(1)
                        ->fetch();  
            
            return $row;   
        }
        
        
        /**
         * Vypocte uroven zanoreni polozky 
         * Jen pro sorting = tree
         * @param array $row
         * @return array
         */
        public function calcItemLevel(array $row = [])
        {
            $parentId = $row['parent']??0;
            $level = 1;

            if ($parentId) {
                do {
                    $parentId = $this->fetchItem($parentId)->parent??0;
                    $level++;

                } while ($parentId > 0);        
            }
            $row['level'] = $level;

            return $row;
        }
        
        
        /**
         * Vypocte pomocne parametry polozek pro seznam - first / last
         * Neboli zdali je prvni ci posledni potomek sveho rodice
         * @param array $row
         * @return array
         */
        public function calcItemFirstLast(array $row = [])
        {

            $first = false;
            $last  = false;

            if (isset($row['order'])) {

                if ($row['order'] == 1) {
                    $first = true;
                }
                
                if (isset($row['parent'])) {
                    $siblingsCount = $this->getItemsCount(['order > ?' => 0, 'parent' => $row['parent']]);
                } else {
                    $siblingsCount = $this->getItemsCount(['order > ?' => 0]);   
                }

                if ($row['order'] == $siblingsCount) {
                    $last = true;
                }        
            }

            $row['first'] = $first;
            $row['last']  = $last; 

            return $row;
        }              


        /**
         * Vrati defaultni razeni polozek podle typu razeni
         * @return string
         */
        public function getDefaultSorting()
        {
            switch ($this->MODUL['sorting'])
            {
                case 'tree':
                    $orderBy = 'orderAbs ASC, id ASC';
                break;
                case 'simple':
                    $orderBy = 'order ASC, id ASC';                   
                break;
                case 'none':
                    $orderBy = 'id ASC';
                break;
                default:
                    $orderBy = $this->MODUL['sorting'];
                break;
            }       
            return $orderBy;
        }  
            
 
        /**
         * Vrati validni url v ramci modulu
         * Pokud je url jiz pouzito, pouziji 
         * se appendy item-2, item-3, .. dokud neprojde
         * @param string $name
         * @return string
         */
        public function getValidItemUrl(string $name, int $excludedId = null, string $_locale = '') 
        {

            $prevodni_tabulka = Array(
            'ä'=>'a','Ä'=>'A','á'=>'a','Á'=>'A','à'=>'a','À'=>'A','ã'=>'a','Ã'=>'A','â'=>'a','Â'=>'A','č'=>'c','Č'=>'C','ć'=>'c','Ć'=>'C','ď'=>'d','Ď'=>'D','ě'=>'e','Ě'=>'E','é'=>'e','É'=>'E','ë'=>'e','Ë'=>'E','è'=>'e','È'=>'E','ê'=>'e','Ê'=>'E','í'=>'i','Í'=>'I','ï'=>'i','Ï'=>'I','ì'=>'i','Ì'=>'I','î'=>'i','Î'=>'I','ľ'=>'l','Ľ'=>'L','ĺ'=>'l','Ĺ'=>'L','ń'=>'n','Ń'=>'N','ň'=>'n','Ň'=>'N','ñ'=>'n','Ñ'=>'N','ó'=>'o','Ó'=>'O','ö'=>'o','Ö'=>'O','ô'=>'o','Ô'=>'O','ò'=>'o','Ò'=>'O','õ'=>'o','Õ'=>'O','ő'=>'o','Ő'=>'O','ř'=>'r','Ř'=>'R','ŕ'=>'r','Ŕ'=>'R','š'=>'s','Š'=>'S','ś'=>'s','Ś'=>'S','ť'=>'t','Ť'=>'T','ú'=>'u','Ú'=>'U','ů'=>'u','Ů'=>'U','ü'=>'u','Ü'=>'U','ù'=>'u','Ù'=>'U','ũ'=>'u','Ũ'=>'U','û'=>'u','Û'=>'U','ý'=>'y','Ý'=>'Y','ž'=>'z','Ž'=>'Z','ź'=>'z','Ź'=>'Z',' '=>'-'
            );

            $url0 = strtr($name, $prevodni_tabulka);
            $url0 = mb_strtolower($url0);
            $url0 = preg_replace("/[^0-9a-z-]/", "", $url0);

            $i = 1;
            do {
                $url = $url0.($i > 1 ? '-'.$i : '');
                $i++;
            } while ($this->checkIfItemUrlExists($url, $excludedId, $_locale));

            return $url;
        } 

        
        
        /**
         * Overi zdali je zadane url v ramci modulu dostupne
         * @param string $url
         * @return bool
         */
        public function checkIfItemUrlExists(string $url, int $excludedId = null, string $_localeDb = '') 
        {
            $row = $this->modulTable()
                        ->where("url$_localeDb = ?", $url);
            
            if ($excludedId) {
                $row->where('id != ?', $excludedId);
            }
            
            return (bool)($row->fetch()->id ?? 0);
        }         
        
        
        /**
         * Zahashuje heslo
         * @param string $password
         * @return string
         */
        public function hashPassword(string $password) 
        {
            $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
            return $passwords->hash($password);  
        }      
}

