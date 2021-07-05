<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class Admin{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('back/dashboard', 'back',  $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

}