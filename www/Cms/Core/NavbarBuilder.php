<?php

namespace CMS\Core;
use CMS\Models\Page;

class NavbarBuilder
{

	public function renderFrontNavigation($site): void
	{
		$pageObj = new Page();
		$pageObj->setPrefix($site->getPrefix());
        $pageObj->setCategory('IS NULL');
        $pagesToShow = $pageObj->findAll();

		$theme = $site->getTheme()??"Default";
		
		$theme = strlen($theme)>0 ? $theme : "Default"; 

		if(file_exists("CMS/Views/Front/".$theme."/navigation.php")){
			include_once("CMS/Views/Front/".$theme."/navigation.php");
		}else{
			die('navbar not found');
		}
	}

	public function renderBackNavigation($site): void
	{
		if(file_exists("CMS/Views/Back/navigation.php")){
			include "CMS/Views/Back/navigation.php";
		}else{
			die('navbar not found');
		}	
	}

}






