<?php
namespace CMS\Core\Router;
use CMS\Core\Router\RouterInterface;

use App\Models\Site;
use App\Models\Whitelist;
use App\Core\Security;

use App\Core\Router;

class AdminRouter extends Router implements RouterInterface
{
	private $uri;
	private $site;

	public function __construct($uri){
		if( !Security::isConnected()){
            header('Location: '.DOMAIN . '/login');
            exit();
        }
        $siteObj = new Site();
        $siteObj->setSubDomain($uri[0]);
        $site = $siteObj->findOne();
        if(!$site || empty($site['id'])){
            header("Location: " . DOMAIN );
            exit();
        }
        if( $site['creator'] !== Security::getUser()){
            $wlistObj = new Whitelist();
            $wlistObj->setIdSite($site['id']);
            $wlistObj->setIdUser(Security::getUser());
            $wlist = $wlistObj->findOne();
            if( !$wlist ){
                header("Location: " . DOMAIN . "/site/" . $site['subDomain']);
                exit();
            }
        }
		$uri = array_slice($uri, 2);
        $uri[0] = empty($uri[0]) ? '/' : ('/' . $uri[0]);
        $uri = implode($uri, '/');
		parent::__construct($uri, "Cms/routes.yml");
		$this->uri  = $uri;
		$this->site = $site;
	}

	public function route(): void{	
		try{
            $c = $this->getController();
            $a = $this->getAction();
    
			if(!file_exists("Cms/Controllers/".$c.".php")) throw new Exception("Le fichier controller : ".$c." n'existe pas");
			include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
			if(!class_exists($c)) throw new Exception("La classe controller : ".$c." n'existe pas");
			$cObjet = new $c();
			if(!method_exists($cObjet, $a)) throw new Exception("L'action' : ".$a." n'existe pas");
			$cObjet->$a($this->site);
		}catch(\Exception $e){
			echo $e->getMessage();
		}

	}


}
