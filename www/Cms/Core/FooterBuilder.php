<?php

namespace CMS\Core;
use CMS\Models\Page;

class FooterBuilder
{

	public function renderFrontFooter($site): void
	{
		// $pageObj = new Page();
		// $pageObj->setPrefix($site->getPrefix());
        // $pageObj->setCategory('IS NULL');
		// $pageObj->setVisible(true);
        // $pagesToShow = $pageObj->findAll();

		$theme = $site->getTheme()??"Default";
		
		$theme = strlen($theme)>0 ? $theme : "Default"; 

		if(file_exists("CMS/Views/Front/".$theme."/footer.php")){
			include_once("CMS/Views/Front/".$theme."/footer.php");
		}else{
			die('Footer not found');
		}
	}

}






