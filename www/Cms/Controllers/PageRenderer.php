<?php
namespace CMS\Controller;
use App\Core\Database as db;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Content;

class PageRenderer 
{
    private $site;
    private $page;
    private $content;
    private $category = null;
    private $path;
    private $error = null;
    private $exist = true;

	public function __construct($url){
        $this->path     = $url;
        $this->domain   = $url[0];
        $this->setParams($url);
	}

    private function setParams($url){
        $siteData = new Site();
        $siteData->setSubDomain($this->domain);
        $site = $siteData->findOne();
        if(empty($site['id'])){
            $this->exist = false;
            return;
        }

        $siteData->setId($site['id']);
        $siteData->setName($site['name']);
        $siteData->setDescription($site['description']);
        $siteData->setImage($site['image']);
        $siteData->setCreator($site['creator']);
        $siteData->setSubDomain($site['subDomain']);
        $siteData->setPrefix($site['prefix']);
        $siteData->setType($site['type']);
        $this->site = $siteData;

        if(empty($url[1])){ 
            //Verify what is the default page of the site
            $url[1] = 'home';
        }
        $pageName = $url[1]??'home';
        $page = new Page($pageName, $this->site->getPrefix());
        $pageData = $page->findOne();
        if(empty($pageData['id'])){
            $this->error = 'The requested page does not exist :/';
            return;
        }
        $page->setId($pageData['id']);
        $this->page = $page;

        $contentObj = new Content();
        $contentObj->setTableName($site['prefix']);
        $contentObj->setPage($pageData['id']);
        $content = $contentObj->findOne();
        $this->content = $content;

    }

    public function renderPage(){
        if(!$this->exist){
            echo 'This website does not exist :/';
            return;
        }

        if($this->error){
            echo $this->error;
            return;
        }

        $actionObj = new Action();
        $actionObj->setId($this->content['method']);
        $action = $actionObj->findOne();
        
        $c = $action['controller'];
        $a = $action['method'];
        $f = $this->content['filter'];
        $debug = 'debug: ' . $a . ' ' . $c . ' ' . $f . '<br>';
        $content = "action not working";

        if( file_exists("Cms/Controllers/".$c.".php")){
            include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
            if(class_exists($c)){
                $cObjet = new $c();
                if(method_exists($cObjet, $a)){
                    $content = $cObjet->$a($this->site, $f);
                }else{
                    //die("L'action' : ".$a." n'existe pas");
                }
            }else{
                //die("La classe controller : ".$c." n'existe pas");
            }
        }else{
            //die("Le fichier controller : ".$c." n'existe pas");
        }
        $content .= $debug;

        $view = new View('cms', 'front');
        $view->assign("pageTitle", ($this->site->getName())??'Page');
        $view->assign("navbar", NavbarBuilder::renderNavbar($this->site->returnData(), 'front'));
        $view->assign("style", StyleBuilder::renderStyle($this->site->returnData()));
        $view->assign("content", $content);
    }

    public function renderNavigation(){
        $pageObj = new Page(null, $this->site->getPrefix());
        $pageObj->setCategory('IS NULL');
        $pagesToShow = $pageObj->findAll();
        $html = "<header>";
        $html .= "<h1>" . $this->site->getName() . "'s restaurant</h1>";
        $html .= '<ul>';
        foreach($pagesToShow as $tab){
            $html .= '<li><a href="/site/' . $this->site->getSubDomain() . '/' . $tab['name'] . '"/>' . $tab['name'] . '</a></li>';
        }
        $html .= '</ul>';
        $html .= "</header>";

        return $html;
    }


}
