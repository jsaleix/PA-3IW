<?php

namespace CMS\Core;

class View
{


	private $template;
	private $view; // home admin login et logout
	private $data = [];

	public function __construct( $view, $template = "back", $extraData = null ){

		$this->setTemplate($template);
		$this->setView($view);
		if(!empty($extraData)){
			extract($extraData);
		}

	}

	public function setTemplate($template){
		if(file_exists("CMS/Views/Templates/".$template.".tpl.php")){
			$this->template = "CMS/Views/Templates/".$template.".tpl.php";
		}else{
			die("Erreur de template");
		}
	}

	public function setView($view){
		if(file_exists("CMS/Views/".$view.".view.php")){
			$this->view = "CMS/Views/".$view.".view.php";
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






