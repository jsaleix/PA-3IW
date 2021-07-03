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
		if(file_exists("CMS/Views/Front/navigation.php")){
			include "CMS/Views/Front/navigation.php";
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






