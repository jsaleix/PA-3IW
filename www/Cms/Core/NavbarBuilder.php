<?php

namespace CMS\Core;
use CMS\Models\Page;

class NavbarBuilder
{

    public static function renderNavbar($site, $type = 'front'){
		if($type === 'front'){
			return self::renderFrontNavbar($site);
		}else{
			return self::renderBackNavbar($site);
		}
	}

	public function renderFrontNavigation($site): void
	{
		$pageObj = new Page();
		$pageObj->setPrefix($site->getPrefix());
        $pageObj->setCategory('IS NULL');
        $pagesToShow = $pageObj->findAll();
		if(file_exists("CMS/Views/Front/navbar.view.php")){
			include "CMS/Views/Front/navbar.view.php";
		}else{
			die('navbar not found');
		}
	}

	public function renderBackNavigation($site): void
	{
		if(file_exists("CMS/Views/Back/navbar.view.php")){
			include "CMS/Views/Back/navbar.view.php";
		}else{
			die('navbar not found');
		}	
	}

}






