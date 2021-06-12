<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

class MenuController{


	public function manageMenusAction($site){
		$html = 'Default Menu action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}


		


}