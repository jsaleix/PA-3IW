<?php

namespace CMS\Core;
use App\Core\View;

class CMSView extends View
{
	protected $navbar;
	public $site;

	public function __construct( $view, $template = "back", $site = null ){
		
		
		// Chemin du theme: Views/Front/Default
		
		$this->setBaseDir('CMS/');
		$this->setTemplate($template);	

		
		if($template == "back" || $template == "Back"){
			$this->setView("Back/".$view);
		}else{
			$template = ucfirst($template);
			$theme = "Default";

			if(gettype($site) == "array"){
				$theme = $site->getTheme();
			}else if(gettype($site) === "object"){
				$theme = $site->getTheme();
			}
			$theme = strlen($theme)>0 ? $theme : "Default"; 
			$this->setView($template."/".$theme."/".$view);
		}

		if($site){
			$this->site = $site;
		}
	}

}






