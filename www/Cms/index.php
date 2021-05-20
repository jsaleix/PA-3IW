<?php
namespace CMS;

use CMS\Core\Router;
use CMS\Controller\PageRenderer;
use App\Models\Site;

function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = explode('/', $uri);
    $uri = array_slice($uri, 2);

    /*$siteData = new Site();
    $siteData->setSubDomain($uri[0]);
    $site = $siteData->findOne();
    if(empty($site->id)){
        'This site does not exist';
        return;
    }*/
    if(empty($uri[1]) || $uri[1] !== 'admin'){
        /**
         * Récupere le nom du site et le chemin demandé
         * Cherche en base si le site existe et la ressource demandée aussi sinon exception
         * Recup les données
         * renderPage va instancier un objet page et lui transmettre le contenu
         **/
        include "Cms/Controllers/PageRenderer.php";
        $pageRenderer = "CMS\\Controller\\PageRenderer";
        $page = new $pageRenderer($uri);
        $page->renderPage();

    }else{
        if(empty($uri[2])){ return; }
        $uri = array_slice($uri, 2);
        $uri[0] = '/' . $uri[0];
        $uri = implode($uri, '/');
        $router = new Router($uri);
        $c = $router->getController();
        $a = $router->getAction();

        if( file_exists("Cms/Controllers/".$c.".php")){
            include "Cms/Controllers/".$c.".php";
            $c = "CMS\\Controller\\".$c;
            if(class_exists($c)){
                $cObjet = new $c();
                if(method_exists($cObjet, $a)){
                    $cObjet->$a();
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






