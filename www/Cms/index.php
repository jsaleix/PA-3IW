<?php
namespace CMS;

use App\Core\Router;
use App\Models\Site;
use App\Models\Whitelist;
use CMS\Core\PageRenderer;
use App\Core\Security;


function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = explode('/', $uri);
    $uri = array_slice($uri, 2);

    if(empty($uri[1]) || $uri[1] !== 'admin'){
        $page = new PageRenderer($uri);
        $page->renderPage();

    }else{
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
        $router = new Router($uri, "Cms/routes.yml");
        $c = $router->getController();
        $a = $router->getAction();

        if( file_exists("Cms/Controllers/".$c.".php")){
            include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
            if(class_exists($c)){
                $cObjet = new $c();
                if(method_exists($cObjet, $a)){
                    $cObjet->$a($site);
                }else{
                    die("L'action' : ".$a." n'existe pas");
                }
            }else{
                die("La classe controller : ".$c." n'existe pas");
            }
        }else{
            die("Le fichier controller : ".$c." n'existe pas");
        }
    }

}






