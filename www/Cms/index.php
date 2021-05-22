<?php
namespace CMS;

use App\Core\Router;
use App\Models\Site;
use CMS\Controller\PageRenderer;

function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = explode('/', $uri);
    $uri = array_slice($uri, 2);

    if(empty($uri[1]) || $uri[1] !== 'admin'){
        include "Cms/Controllers/PageRenderer.php";
        $pageRenderer = "CMS\\Controller\\PageRenderer";
        $page = new $pageRenderer($uri);
        $page->renderPage();

        

    }else{
        $siteData = new Site();
        $siteData->setSubDomain($uri[0]);
        $site = $siteData->findOne();
        if(empty($site['id'])){
            echo 'This site does not exist <br>';
            return;
        }
        $uri = array_slice($uri, 2);
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






