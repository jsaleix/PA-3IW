<?php
namespace CMS\Core\Router;
use CMS\Core\Router\RouterInterface;
use App\Core\Router;

use App\Models\Site;
use App\Models\Whitelist;
use App\Core\Security;
use CMS\Core\PageRenderer;

class DynamicRouter extends Router implements RouterInterface
{
	private $uri;
	private $controller;
	private $action;
	private $middleware;

	public function __construct($uri){
		$this->uri = $uri;
	}

	public function setUri($uri): void{

	}

	public function setController($controller): void{

	}

	public function setAction($action): void{

	}

	public function getController(): void{

	}

	public function route(): void{
		$page = new PageRenderer($this->uri);
        $page->renderPage();
	}


}
