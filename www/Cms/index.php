<?php
namespace CMS;

use CMS\Core\Router;
use CMS\Core\PageRenderer;
use CMS\Models\Page;

function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = explode('/', $uri);
    array_shift($uri);
    array_shift($uri);
    if($uri[0] !== 'admin'){
        /**
         * Récupere le nom du site et le chemin demandé
         * Cherche en base si le site existe et la ressource demandée aussi sinon exception
         * Recup les données
         * renderPage va instancier un objet page et lui transmettre le contenu
         **/
        $page = new PageRenderer($uri[0], $uri);
        $page->renderPage();
    }

}






