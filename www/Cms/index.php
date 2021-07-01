<?php
namespace CMS;

use CMS\Core\CMSRouterMaker;

function handleCMS($uri){
    if(!$uri){ throw new InvalidArgumentException ('Missing uri parameter');}
    $uri = array_slice(explode('/', $uri), 2); // site/name/page -> [ 'name', 'page' ];

    $router = CMSRouterMaker::make($uri);
    $router->route();
}






