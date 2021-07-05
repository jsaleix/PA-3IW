<?php

namespace CMS\Core;
use App\Core\View;
use CMS\Core\NavbarBuilder;

class CMSView extends View
{
	protected $navbar;
	public $site;

	public function __construct( $view, $template = "back", $site = null ){
		$this->setBaseDir('CMS/');
		$this->setTemplate($template);
		$this->setView($view);
		if($site){
			$this->site = $site;
		}
	}

}






