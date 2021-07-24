<?php
namespace CMS\Core\Router;
use CMS\Core\Router\RouterInterface;
use App\Core\Router;

use App\Core\ErrorReporter;
use App\Models\Site;

use CMS\Models\Page;

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
			$siteCheck = $siteObj->findOne(TRUE);
			if(!$siteCheck){ throw new \Exception('This site does not exist'); }

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
			ErrorReporter::report("EntityRouter Construct():" . $e->getMessage() );
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/');
			return;
		}
	}

	public function route(): void{
		try{
			$c = $this->getController();
			$a = $this->getAction();
	
			$debug = 'debug: ' . $a . ' - ' . $c . '<br>';
			// echo $debug;

			if(!file_exists("Cms/Controllers/".$c.".php")) throw new \Exception("Le fichier controller : ".$c." n'existe pas");
			include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
			if(!class_exists($c)) throw new \Exception("La classe controller : ".$c." n'existe pas");
			$cObjet = new $c();
			if(!method_exists($cObjet, $a)) throw new \Exception("L'action' : ".$a." n'existe pas");
			$cObjet->$a($this->site);
		}catch(\Exception $e){
			echo $e->getMessage();
			ErrorReporter::report("EntityRouter route():" . $e->getMessage() );
		}
	}


}
