<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;

class PageController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function managePagesAction($site){
		$pageObj = new Page(null, $site['prefix']);
		$pages = $pageObj->findAll();
		$pagesList = [];
		
		foreach($pages as $item){
			$pagesList[] = $pageObj->listFormalize($item);
		}
		$createPageBtn = '<a href="createpage"><button>Create</button></a>';

		$view = new View('admin.list', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site));
		$view->assign("content", $createPageBtn);
		$view->assign("list", $pagesList);
		$view->assign('pageTitle', "Manage the pages");
	}

	public function createPageAction($site){
		$categoryObj = new Category();
		$categoryObj->setTableName($site['prefix']);
		$category = $categoryObj->findAll();
		$categoryArr = array();
		$categoryArr[] = 'None';

		foreach($category as $data){
			$categoryArr[$data['id']] = $data['name'];
		}
		$page = new Page(null, $site['prefix']);
		$form = $page->formAddContent($categoryArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a page");

		if(!empty($_POST) ) {
			$erros = [];
			[ "name" => $title, "category" => $category ] = $_POST;
			if( $title ){
				$insert = new Page($title, $site['prefix']);
				if( !empty($category) && $category !== '0'){
					$categoryObj->setId($category);
					$checkCategory = $categoryObj->findOne();
					if(!$checkCategory){
						$errors[] = "The requested category does not exist";
					}
					$insert->setCategory($category);
				}
				$adding = $insert->save();
				if($adding){
					$message ='Page successfully published!';
					$view->assign("message", $message);
				}else{
					$errors[] = "Cannot insert this page";
					$view->assign("errors", $errors);
				}
			}
		}
	}

}