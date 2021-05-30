<?php
namespace CMS\Core;
use App\Core\Database as db;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;
use App\Core\Router;

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
    private $type;
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
        $siteObj = new Site();
        $siteObj->setSubDomain($this->domain);
        $site = $siteObj->findOne();
        if(empty($site['id']) || !$site){
            $this->exist = false;
            return;
        }

        $siteObj->setId($site['id']);
        $siteObj->setName($site['name']);
        $siteObj->setDescription($site['description']);
        $siteObj->setImage($site['image']);
        $siteObj->setCreator($site['creator']);
        $siteObj->setSubDomain($site['subDomain']);
        $siteObj->setPrefix($site['prefix']);
        $siteObj->setType($site['type']);
        $this->site = $siteObj;

        if(empty($url[1])){ 
            //To add: Verifying what is the default page of the site
            $url[1] = 'home';
        }
        $pageName = $url[1]??'home';

        if($pageName != 'ent'){
            $this->type = 'dynamic';
            $pageObj = new Page();
            $pageObj->setName($pageName);
            $pageObj->setPrefix($this->site->getPrefix());
            $pageData = $pageObj->findOne();
            if(empty($pageData['id'])){
                $this->error = 'The requested page does not exist :/';
                return;
            }
            $pageObj->setId($pageData['id']);
            $this->page = $pageObj;
    
            $contentObj = new Content();
            $contentObj->setPrefix($site['prefix']);
            $contentObj->setPage($pageData['id']);
            $content = $contentObj->findOne();
            $this->content = $content;
        }else{
            $this->type = 'entity';
        }
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

        if($this->type == 'dynamic'){
            $content = $this->renderDynamic();
        }else{
            $content = $this->renderEntity();
        }

        $view = new View('cms', 'front');
        $view->assign("pageTitle", ($this->site->getName())??'Page');
        $view->assign("navbar", NavbarBuilder::renderNavbar($this->site->returnData(), 'front'));
        $view->assign("style", StyleBuilder::renderStyle($this->site->returnData()));
        $view->assign("content", $content);
    }

    public function renderDynamic(){
        $content = "action not working";
        $actionObj = new Action();
        $actionObj->setId($this->content['method']);

        try{
            $action = $actionObj->findOne();
            if(!$action){ throw new \Exception('No action found'); }
            $c = $action['controller'];
            $a = $action['method'];
            $f = $this->content['filter'];
            $debug = 'debug: ' . $a . ' ' . $c . ' ' . $f . '<br>';
    
            if( file_exists("Cms/Controllers/".$c.".php")){
                include "Cms/Controllers/".$c.".php";
                $c = "CMS\\Controller\\".$c;
                if(class_exists($c)){
                    $cObjet = new $c();
                    if(method_exists($cObjet, $a)){
                        $content = $cObjet->$a($this->site, $f);
                        $content .= $debug;
                    }else{
                        //die("L'action' : ".$a." n'existe pas");
                    }
                }else{
                    //die("La classe controller : ".$c." n'existe pas");
                }
            }else{
                //die("Le fichier controller : ".$c." n'existe pas");
            }
        }catch(\Exception $e){
            $content = $e->getMessage();
        }
        return $content;
    }

    public function renderEntity(){
        $uri = array_slice($this->path, 1);
        if(empty($uri[0])){
            $uri[0] = '/';
        }else{
            $uri[0] = '/' . $uri[0];
        }
        $uri = implode($uri, '/');

        $router = new Router($uri, "Cms/routes.yml");
        $c = $router->getController();
        $a = $router->getAction();

        if( file_exists("Cms/Controllers/".$c.".php")){
            include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
            if(class_exists($c)){
                $cObjet = new $c();
                if(method_exists($cObjet, $a)){
                    $content = $cObjet->$a($this->site);
                }else{
                    die("L'action' : ".$a." n'existe pas");
                }
            }else{
                die("La classe controller : ".$c." n'existe pas");
            }
        }else{
            die("Le fichier controller : ".$c." n'existe pas");
        }
        return $content;
    }


}
