<?php

namespace CMS\Controller;

use CMS\Core\CMSView as View;

class Admin{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('dashboard', 'back',  $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

}