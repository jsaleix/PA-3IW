<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

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

		$contentObj = new Content();
		$contentObj->setTableName($site['prefix']);
		$actionObj = new Action();

		$categoryObj = new Category();
		$categoryObj->setTableName($site['prefix']);

		foreach($pages as $item){
			if($item['category'] !== NULL){
				$categoryObj->setId($item['category']);
				$category = $categoryObj->findOne();
				$item['category'] = $category['name']??'Unknown';

			}else{
				$item['category'] = $item['category']??'Unknown';
			}

			$contentObj->setPage($item['id']);
			$methodId = $contentObj->findOne();
			$actionObj->setId($methodId['method']);
			$actionName = $actionObj->findOne();
			$item['action'] = $actionName['name'];

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
		$pageObj = new Page(null, $site['prefix']);

		$actionObj = new Action();
		$actions = $actionObj->findAll();
		$actionArr = [];
		if(!empty($actions)){
			foreach($actions as $action){
				$actionArr[$action['id']] = $action['name'];
			}
		}

		$form = $pageObj->formAddContent($actionArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a page");

		if(!empty($_POST) ) {
			$erros = [];
			[ "name" => $name, "action" => $action ] = $_POST;
			if( $name ){
				if( !empty($action) && $action !== '0'){
					$pageObj->setAction($action);
				}
				$pageObj->setName($name);
				$adding = $pageObj->save();
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

	public function editPageAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'page not set ';
			header("Location: managepages");
		}

		$pageObj = new Page(null, $site['prefix']);
		$pageObj->setTableName($site['prefix']);
		$pageObj->setId($_GET['id']??0);
		$page = $pageObj->findOne();
		if(!$page){
			header("Location: managepages");
		}

		$contentObj = new Content();
		$contentObj->setTableName($site['prefix']);
		$contentObj->setPage($_GET['id']);
		$content = $contentObj->findOne();

		$actionObj = new Action();
		$actions = $actionObj->findAll();
		$actionArr = [];
		if(!empty($actions)){
			foreach($actions as $action){
				$actionArr[$action['id']] = $action['name'];
			}
		}

		$categoryObj = new Category();
		$categoryObj->setTableName($site['prefix']);
		$category = $categoryObj->findAll();
		$categoryArr = array();
		$categoryArr[] = 'None';
		if(!empty($category)){
			foreach($category as $data){
				$categoryArr[$data['id']] = $data['name'];
			}
		}
		
		$pageArr = (array)$page;
		$contentArr = (array)$content;
		$contentArr = [ 'action' => $contentArr['method'] ];		
		$pageArr = array_merge((array)$page, $contentArr);

		$form = $pageObj->formEditContent($pageArr, $categoryArr, $actionArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a page");

		if(!empty($_POST) ) {
			[ "name" => $name, "category" => $category, "action" => $action] = $_POST;
			if( $name ){
				$pageObj->setName($name);
				$pageObj->setCategory($category??null);
				$pageObj->setAction($action??null);
				$adding = $pageObj->save();

				if($adding){
					$message ='Page successfully updated!';
					$view->assign("message", $message);
				}else{
					$errors = ["Error when updating this page"];
					$view->assign("errors", $errors);
				}
			}
		}
	}

}