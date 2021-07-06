<?php
namespace CMS\Core\Router;
use CMS\Core\Router\RouterInterface;
use App\Core\Router;

use App\Models\Site;
use App\Models\Whitelist;
use App\Core\Security;

use App\Models\Action;

use CMS\Core\PageRenderer;
use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Content;

class DynamicRouter extends Router implements RouterInterface
{
	private $uri;
	private $controller;
	private $action;

	public function __construct($url){
		try{
			$domain = $url[0];

			$pageObj = new Page();
			$siteObj = new Site();
			$requestedPage = $url[1];

			$siteObj->setSubDomain($domain);
			$site = $siteObj->findOne();
			if(empty($site['id']) || !$site){ throw new \Exception('This site does not exist'); }

			$siteObj->setId($site['id']);
			$siteObj->setName($site['name']);
			$siteObj->setDescription($site['description']);
			$siteObj->setImage($site['image']);
			$siteObj->setCreator($site['creator']);
			$siteObj->setSubDomain($site['subDomain']);
			$siteObj->setPrefix($site['prefix']);
			$siteObj->setType($site['type']);
			$siteObj->setTheme($site['theme']);
			
			$this->site = $siteObj;

			if(empty($requestedPage)){ //Verifying what is the default page of the site
				$pageObj->setPrefix($site['prefix']);
				$page = $pageObj->findAll();
				$requestedPage = $page[0]['name'];
				\App\Core\Helpers::customRedirect('/' . $requestedPage, $site);
			}

			$pageObj->setName($requestedPage);
			$pageObj->setPrefix($this->site->getPrefix());
			$pageData = $pageObj->findOne();
			if(empty($pageData['id'])){ //The page is not found
				\App\Core\Helpers::errorStatus();
			}
			$pageObj->setId($pageData['id']);
			$this->page = $pageObj;

			$contentObj = new Content();
			$contentObj->setPrefix($site['prefix']);
			$contentObj->setPage($pageData['id']);
			$content = $contentObj->findOne();

			$actionObj = new Action();
			$actionObj->setId($content['method']);
			$action = $actionObj->findOne();
			if(!$action){ throw new \Exception('No action found'); }

			$this->setAction($action['method']);
			$this->setController($action['controller']);
			$this->setFilter($content['filter']);

		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/');
		}
	}

	public function getAction(){
		return $this->action;
	}

	public function setAction($action){
		$this->action = $action;
	}

	public function setFilter($filter){
		$this->filter = $filter;
	}

	public function getFilter(){
		return $this->filter;
	}

	public function route(): void{
		try{
			$c = $this->getController();
			$a = $this->getAction();
			$f = $this->getFilter();
			$debug = 'debug: ' . $a . ' - ' . $c . ' - ' . $f . '<br>';
			// echo $debug;

			if(!file_exists("Cms/Controllers/".$c.".php")) throw new \Exception("Le fichier controller : ".$c." n'existe pas");
			include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
			if(!class_exists($c)) throw new \Exception("La classe controller: ".$c." n'existe pas");
			$cObjet = new $c();
			if(!method_exists($cObjet, $a)) throw new \Exception("L'action: ".$a." n'existe pas");
			$cObjet->$a($this->site, $f);
		}catch(\Exception $e){
			echo $e->getMessage();
		}
	}


}
