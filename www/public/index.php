<?php
use App\Core\Router;
use App\Core\ConstantMaker;
use App\Core\Helpers;
use App\Core\ErrorReporter;
use App\Middlewares\Middleware;
use App\Autoload;

require __DIR__."/../Autoload.php";

define('STYLES', "/Assets/styles/main.css"); 

session_start();

Autoload::register();
new ConstantMaker();

$uriExploded = explode("?", $_SERVER["REQUEST_URI"]);
$uri = $uriExploded[0];

/*
*	If the url pattern matches /site we must use the cms part of the project
*	with its own routers mecanisms
*/
if( preg_match('/\/site\/+/', $uri) ){
	if( file_exists(__DIR__.'/../Cms/index.php') ){
		include __DIR__.'/../Cms/index.php';
		\CMS\handleCMS($uri);
	}else{
		ErrorReporter::report("index for /site: Missing required cms file" );
		Helpers::serverErrorStatus();
	}
	return;
}


$router = new Router($uri, __DIR__."/../routes.yml");
$c = $router->getController();
$a = $router->getAction();
$m = $router->getMiddleware();

if($m){
	Middleware::$m();
}

try{
	if( !file_exists(__DIR__."/../Controllers/".$c.".php")){
		throw new \Exception("Missing controller file: ".$c);
	}
	include __DIR__."/../Controllers/".$c.".php";
	$c = "App\\Controller\\".$c;

	if( !class_exists($c) ){
		throw new \Exception("Missing controller class: ".$c);
	}
	$cObjet = new $c();

	if( !method_exists($cObjet, $a) ){
		throw new \Exception("Action: ".$a." doesn't exist");
	}
	$cObjet->$a();

}catch(\Exception $e){
	ErrorReporter::report("index: " . $e->getMessage() );
	Helpers::serverErrorStatus();
}
