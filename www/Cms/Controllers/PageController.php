<?php

namespace CMS\Controller;
use App\Core\Security;

use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class PageController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function managePagesAction($site){
		$pageObj = new Page();
		$pageObj->setPrefix($site['prefix']);
		$pages = $pageObj->findAll();
		$fields = [ 'id', 'name', 'category', 'creator', 'action', 'edit', 'delete'];
		$datas = [];

		$contentObj = new Content();
		$contentObj->setPrefix($site['prefix']);

		$actionObj = new Action();
		$userObj = new User();

		$categoryObj = new Category();
		$categoryObj->setPrefix($site['prefix']);

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

			if($methodId['method'] !== NULL){
				$actionObj->setId($methodId['method']);
				$actionName = $actionObj->findOne();
				$item['action'] = $actionName['name'];
			}else{
				$item['action'] = 'Unknown action';
			}

			if($item['creator'] !== NULL){
				$userObj->setId($item['creator']);
				$user = $userObj->findOne();
				$item['creator'] = $user['firstname'] . ' ' . $user['lastname'];
			}


			$buttonEdit = '<a href="editPage?id=' . $item['id'] . '">Go</a>';
			$buttonDelete = '<a href="deletePage?id=' . $item['id'] . '">Go</a>';
			$datas[] = "'".$item['id']."','".$item['name']."','".$item['category']."','".$item['creator']. "','" . $item['action'] . "','" . $buttonEdit . "','" . $buttonDelete ."'";

		}
		$createPageBtn = ['label' => 'Create a page', 'link' => 'createpage'];

		$view = new View('back/list', 'back', $site);
		$view->assign("createButton", $createPageBtn);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the pages");
	}

	public function createPageAction($site){
		$pageObj = new Page();
		$pageObj->setPrefix($site['prefix']);
		$actionObj = new Action();
		$actions = $actionObj->findAll();
		$actionArr = [];
		if(!empty($actions)){
			foreach($actions as $action){
				$actionArr[$action['id']] = $action['name'];
			}
		}

		$form = $pageObj->formAddContent($actionArr);

		$view = new View('admin.create', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a page");

		if(!empty($_POST) ) {
			$erros = [];
			[ "name" => $name, "action" => $action, "filters" => $filters ] = $_POST;
			if( $name ){
				if( !empty($action) && $action !== '0'){
					$pageObj->setAction($action);
				}
				$pageObj->setName($name);
				$pageObj->setCreator(Security::getUser());
				if($filters){
					$pageOb->setFilters(htmlspecialchar($filters));
				}
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
			exit();
		}

		$pageObj = new Page();
		$pageObj->setPrefix($site['prefix']);
		$pageObj->setId($_GET['id']??0);
		$page = $pageObj->findOne();
		if(!$page){
			header("Location: managepages");
			exit();
		}

		$contentObj = new Content();
		$contentObj->setPrefix($site['prefix']);
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
		$categoryObj->setPrefix($site['prefix']);
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

		$form = $pageObj->formEditContent($pageArr, $categoryArr, $actionArr, ($content['filter']));

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a page");

		if(!empty($_POST) ) {
			[ "name" => $name, "category" => $category, "action" => $action, "filters" => $filters] = $_POST;
			if( $name ){
				$pageObj->setName($name);
				$pageObj->setCategory($category??null);
				$pageObj->setAction($action??null);
				$pageObj->setFilters(($filters));
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

	public function deletePageAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'page not set ';
			header("Location: managepages");
			exit();
		}
		$pageObj = new Page();
		$pageObj->setPrefix($site['prefix']);
		$pageObj->setId($_GET['id']??0);
		$page = $pageObj->findOne();
		print_r($page);
		if(!$page){
			header("Location: managepages");
			exit();
		}
		$pageObj->delete();
		header("Location: managepages");
		exit();

	}

}