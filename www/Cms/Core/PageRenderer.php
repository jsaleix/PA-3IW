<?php
namespace CMS\Core;
use App\Core\Database as db;

class PageRenderer 
{
    private $siteName;
    private $prefix;
    private $siteId;

    private $name;
    private $pageId;
    private $category = null;
    private $path;
    private $error = null;

    
	public function __construct($name, $url){
        $this->path = $url;
        $this->siteName = $name;
        $this->setParams($url);
        //Verifier si la page et le site existent
	}

    private function setParams($value){
        $category = [];
        while( count($value) > 1 ){
            $removed = array_shift($value);
            if($removed){
                $category[] = $removed;
            }
        }
        $this->name = $value[0];
        $this->category = $category;
        $db = new db();
        $checkSite = $db->find("SELECT name, id, prefix FROM ag_Site WHERE subDomain = ?", [$this->siteName]);
        //echo var_dump($checkSite);

        $this->siteId = $checkSite['id'];
        $this->prefix = $checkSite['prefix'];

        if(!$checkSite){
            $this->error = 'This website does not exist :/';
        }
        $checkPage =  $db->find("SELECT id, category FROM {$this->prefix}_page WHERE name = ?", [$this->name]);
        if(!$checkPage){
            $this->error = 'The requested page does not exist (anymore?)';
        }
        $this->pageId = $checkPage['id'];
        
    }

    public function renderPage(){
        if($this->error){
            echo $this->error;
            return;
        }
        $db = new db();
        $contents = $db->getAll("SELECT * FROM {$this->prefix}_content WHERE page = ? ", [$this->pageId]);

        if($contents === 0){
            echo 'No content found :/';
            return;
        }

        foreach($contents as $content){
            //print_r($content);
            echo $content['content'] . '<br>';
        }
        
    }
    

}
