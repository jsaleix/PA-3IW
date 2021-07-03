<?php
namespace CMS\Core;
use CMS\Core\Router\RouterInterface;
use CMS\Core\Router\DynamicRouter;
use CMS\Core\Router\AdminRouter;
use CMS\Core\Router\EntityRouter;

class CMSRouterMaker
{
	static function make(array $uri): RouterInterface
	{
		if(empty($uri[1]) || $uri[1] !== 'admin' && $uri[1] !== 'ent'){ // if there is no page name or pageName is different than admin 
			return new DynamicRouter($uri);
		}else{
			if($uri[1] === 'admin')
				return new AdminRouter($uri);
			if($uri[1] === 'ent')
				return new EntityRouter($uri);
		}
	}

}
