<?php
namespace CMS\Core;

class PageManager
{
    private $name;
    private $category = null;
    private $path;

	public function __construct($name, $url){
        $this->setParams($url);
        $this->path = $url;

        //Verifier si la page et le site existent
	}

    public function renderPage(){
        echo "Voici la page {$this->name} rendue !";
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
    }

}
