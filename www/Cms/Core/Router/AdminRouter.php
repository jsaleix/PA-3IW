<?php
namespace CMS\Core\Router;
use CMS\Core\Router\RouterInterface;

use App\Core\ErrorReporter;
use App\Models\Site;
use App\Models\Whitelist;
use App\Core\Security;

use App\Core\Router;

class AdminRouter extends Router implements RouterInterface
{
	private $uri;
	private $site;

	public function __construct($uri){
        try{
            if( !Security::isConnected()){
                \App\Core\Helpers::customRedirect('/login');
            }

            $site = new Site();
            $site->setSubDomain($uri[0]);
            $site->findOne(TRUE);
            if(!$site || empty($site->getId())){ throw new \Exception('This site does not exist'); }

            if( $site->getCreator() !== Security::getUser()){
                $wlistObj = new Whitelist();
                $wlistObj->setIdSite($site->getId());
                $wlistObj->setIdUser(Security::getUser());
                $wlist = $wlistObj->findOne();
                if( !$wlist ) { throw new \Exception('You\re not allowed to access this page'); }
            }
            $uri = array_slice($uri, 2);
            $uri[0] = empty($uri[0]) ? '/' : ('/' . $uri[0]);
            $uri = implode($uri, '/');
            parent::__construct($uri, __DIR__."/routes_admin.yml");
            $this->uri  = $uri;
            $this->site = $site;
        }catch(\Exception $e){
            ErrorReporter::report("AdminRouter Construct():" . $e->getMessage() );
			//echo $e->getMessage();
            \App\Core\Helpers::customRedirect('/', $site);
		}
	}

	public function route(): void{	
		try{
            $c = $this->getController();
            $a = $this->getAction();
    
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/CMS/Controllers/".$c.".php")) throw new \Exception("Le fichier controller : ".$c." n'existe pas");
			include $_SERVER['DOCUMENT_ROOT'] . "/CMS/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
			if(!class_exists($c)) throw new \Exception("La classe controller : ".$c." n'existe pas");
			$cObjet = new $c();
			if(!method_exists($cObjet, $a)) throw new \Exception("L'action' : ".$a." n'existe pas");
			$cObjet->$a($this->site);
		}catch(\Exception $e){
			//echo $e->getMessage();
            ErrorReporter::report("AdminRouter route():" . $e->getMessage() );
		}
	}


}
