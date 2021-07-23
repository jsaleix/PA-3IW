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
		$pageObj->setVisible(true);
        $pagesToShow = $pageObj->findAll();

		$theme = $site->getTheme()??"Default";
		
		$theme = strlen($theme)>0 ? $theme : "Default"; 

		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/CMS/Views/Front/".$theme."/navigation.php")){
			include_once($_SERVER['DOCUMENT_ROOT'] . "/CMS/Views/Front/".$theme."/navigation.php");
		}else{
			die('navbar not found');
		}
	}

	public function renderBackNavigation($site): void
	{
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/CMS/Views/Back/navigation.php")){
			include $_SERVER['DOCUMENT_ROOT'] . "/CMS/Views/Back/navigation.php";
		}else{
			die('navbar not found');
		}	
	}

}






