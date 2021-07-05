<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;
use App\Core\FileUploader;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;
use CMS\Models\Medium;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class MediaController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function listMediasAction($site){
		$view = new View('back/manageLibrary', 'back', $site);
		$view->assign('pageTitle', "Manage the dishes");
	}

	public function createMediumAction($site){
		$mediumObj = new Medium();
		$mediumObj->setPrefix($site['prefix']);

		$view = new View('admin.create', 'back');

	}

	public function editMediumAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) )
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj = new Medium();
		$mediumObj->setPrefix($site['prefix']);
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		
		print_r($medium);

	}

	public function deleteMediumAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) )
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj = new Medium();
		$mediumObj->setPrefix($site['prefix']);
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		echo "Would have delete Medium with id ".$mediumObj->getId()." but working on it tho it's disabled";
		//$mediumObj->delete();
		//\App\Core\Helpers::customRedirect('/admin/medium', $site);
	}

}