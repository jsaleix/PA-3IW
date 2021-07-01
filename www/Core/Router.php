<?php
namespace App\Core;

class Router
{
	private $routes = [];
	private $uri;
	private $routesPath = "routes.yml";
	private $controller;
	private $action;
	private $middleware;

	public function __construct($uri, $routePath){
		$this->routesPath = $routePath;
		$this->setUri($uri);
		if(file_exists($this->routesPath)){
			//[/] => Array ( [controller] => Global [action] => default )
			$this->routes = yaml_parse_file($this->routesPath);

			if( !empty($this->routes[$this->uri]) 
				&& $this->routes[$this->uri]["controller"]
				&& $this->routes[$this->uri]["action"]){
			
				$this->setController($this->routes[$this->uri]["controller"]);
				$this->setAction($this->routes[$this->uri]["action"]);
				if( !empty($this->routes[$this->uri]["middleware"]))
					$this->setMiddleware($this->routes[$this->uri]["middleware"]);
			}else{
				die("Chemin inexistant : 404");
			}

		}else{
			die("Le fichier routes.yml ne fonctionne pas !");
		}
	}

	public function setUri($uri){
		$this->uri = trim(mb_strtolower($uri));

	}


	public function setController($controller){
		$this->controller = $controller;
	}


	public function setAction($action){
		$this->action = $action."Action";
	}


	public function getController(){
		return $this->controller;
	}


	public function getAction(){
		return $this->action;
	}

	public function setMiddleware($middleware){
		$this->middleware = $middleware;
	}

	public function getMiddleware(){
		return $this->middleware;
	}

}
