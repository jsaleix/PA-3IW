<?php
namespace CMS\Core;
use CMS\Core\Router\RouterInterface;
use CMS\Core\Router\DynamicRouter;
use CMS\Core\Router\FileRouter;

class CMSRouterMaker
{
	static function make(array $uri): RouterInterface
	{
		if(empty($uri[1]) || $uri[1] !== 'admin'){ // if there is no page name or pageName is different than admin 
			return new DynamicRouter($uri);
		}else{
			return new FileRouter($uri);
		}
	}

}
