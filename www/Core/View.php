<?php

namespace App\Core;

class View
{

	protected $template;
	protected $view; 
	protected $data = [];
	protected $baseDir = '';

	public function __construct( $view, $template = "front" ){
		$this->setTemplate($template);
		$this->setView($view);
	}

	protected function setBaseDir(String $path): void {
		$this->baseDir = $path;
	}

	public function setTemplate($template){
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $this->baseDir . "Views/Templates/".$template.".tpl.php")){
			$this->template = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->baseDir . "Views/Templates/".$template.".tpl.php";
		}else{
			die("Erreur de template");
		}
	}

	public function setView($view){
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $this->baseDir . "Views/".$view.".view.php")){
			$this->view = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->baseDir . "Views/".$view.".view.php";
		}else{
			die("Erreur de vue");
		}
	}

	public function assign($key, $value){
		$this->data[$key] = $value;
	}

	public function __destruct(){
		extract($this->data);

		include $this->template;
	}


}






