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

class EntityRouter extends Router implements RouterInterface
{
	private $uri;
	private $controller;
	private $action;
	private $middleware;
	private $domain;
	private $path;

	public function __construct($url){
		try{
			$domain = $url[0];

			$pageObj = new Page();
			$siteObj = new Site();
			$requestedPage = $url[1];

			$siteObj->setSubDomain($domain);
			$site = $siteObj->findOne();

			if(empty($site['id']) || !$site){
				throw new \Exception('This site does not exist');
			}

			$siteObj->setId($site['id']);
			$siteObj->setName($site['name']);
			$siteObj->setDescription($site['description']);
			$siteObj->setImage($site['image']);
			$siteObj->setCreator($site['creator']);
			$siteObj->setSubDomain($site['subDomain']);
			$siteObj->setPrefix($site['prefix']);
			$siteObj->setType($site['type']);

			$uri = array_slice($url, 1);
			if(empty($uri[0])){
				$uri[0] = '/';
			}else{
				$uri[0] = '/' . $uri[0];
			}
			$uri = implode($uri, '/');
			parent::__construct($uri, "Cms/routes.yml");
			$this->uri  = $uri;
			$this->site = $siteObj;

		}catch(\Exception $e){
			echo $e->getMessage();
			return;
		}
	}

	public function route(): void{
		try{
			$c = $this->getController();
			$a = $this->getAction();
	
			$debug = 'debug: ' . $a . ' - ' . $c . '<br>';
			echo $debug;

			if(!file_exists("Cms/Controllers/".$c.".php")) throw new \Exception("Le fichier controller : ".$c." n'existe pas");
			include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
			if(!class_exists($c)) throw new \Exception("La classe controller : ".$c." n'existe pas");
			$cObjet = new $c();
			if(!method_exists($cObjet, $a)) throw new \Exception("L'action' : ".$a." n'existe pas");
			$cObjet->$a($this->site);
		}catch(\Exception $e){
			echo $e->getMessage();
		}
	}


}
