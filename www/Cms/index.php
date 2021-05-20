<?php
namespace CMS;

use CMS\Core\Router;
use CMS\Controller\PageRenderer;

function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = explode('/', $uri);
    $uri = array_slice($uri, 2);
    print_r($uri);
    if($uri[1] !== 'admin'){
        /**
         * Récupere le nom du site et le chemin demandé
         * Cherche en base si le site existe et la ressource demandée aussi sinon exception
         * Recup les données
         * renderPage va instancier un objet page et lui transmettre le contenu
         **/
        include "Cms/Controllers/PageRenderer.php";
        $pageRenderer = "CMS\\Controller\\PageRenderer";
        $page = new $pageRenderer($uri[0], $uri);
        $page->renderPage();
        
    }else{
        if(empty($uri[2])){ return; }
        $uri = array_slice($uri, 2);
        $uri[0] = '/' . $uri[0];
        $uri = implode($uri, '/');
        $router = new Router($uri);
        $c = $router->getController();
        $a = $router->getAction();
        echo $c ;

        if( file_exists("Cms/Controllers/".$c.".php")){

            include "Cms/Controllers/".$c.".php";
            // SecurityController =>  App\Controller\SecurityController
        
            $c = "CMS\\Controller\\".$c;
            if(class_exists($c)){
                // $controller ====> SecurityController
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






