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
		$mediumObj = new Medium();
		$mediumObj->setPrefix($site['prefix']);
		$media = $mediumObj->findAll();
		$mediumList = [];
		$content = "";
		$fields = ['id', 'image', 'name', 'publicationDate', 'Edit', 'Delete'];
		$datas = [];

		if($media){
			foreach($media as $item){
				$img = '<img src='.DOMAIN.'/'.$item['path'].' width=100 height=80/>';
				$buttonEdit = '<a href="medium/edit?id='.$item['id'].'">Go</a>';
				$buttonDelete = '<a href="medium/delete?id='.$item['id'].'">Go</a>';
				$formalized = "'".$item['id']."','".$img."','".$item['name']."','".$item['publicationDate']."','".$buttonEdit."','".$buttonDelete."'";
				$datas[] = $formalized;
			}
		}
		$addMediumButton = ['label' => 'Add a new Medium', 'link' => 'medium/create'];

		$view = new View('back/list', 'back', $site);
		$view->assign("createButton", $addMediumButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
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