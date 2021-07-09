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
			$this->setView("back/".$view);
		}else{
			$theme = "Default";

			if(gettype($site) == "array"){
				$theme = $site['theme'];
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






