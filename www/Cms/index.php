<?php
namespace CMS;

use CMS\Core\CMSRouterMaker;

function handleCMS($uri){
    // $uri = // site/subDomain/page
    $uri = explode('/', $uri);
    $uri = array_slice($uri, 2); // [ '', 'site', 'subDomain', 'page' ] -> [ 'subDomain', 'page' ];

    try{
        if(!$uri){ throw new \InvalidArgumentException ('Missing uri parameter');}
        if(!$uri[0]){ throw new \InvalidArgumentException ('Missing uri parameter index 0');}
    }catch(\Exception $e){
        echo $e->getMessage();
        \App\Core\Helpers::errorStatus();
    }
    
    $router = CMSRouterMaker::make($uri);
    $router->route();
}






