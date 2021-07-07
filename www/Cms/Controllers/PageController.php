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
		$pageObj = new Page($site['prefix']);
		$pages = $pageObj->findAll();
		$fields = [ 'id', 'name', 'category', 'creator', 'action', 'main page', 'visible in navigation', 'edit', 'delete', 'see', 'copyLink'];
		$datas = [];

		$contentObj = new Content($site['prefix']);

		$actionObj 	= new Action();
		$userObj 	= new User();

		$categoryObj = new Category($site['prefix']);

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

			$buttonEdit 	= '<a href="page/edit?id=' . $item['id'] . '">Go</a>';
			$buttonDelete	= '<a href="page/delete?id=' . $item['id'] . '">Go</a>';
			$buttonVisit 	= '<a href="'. \App\Core\Helpers::renderCMSLink($item['name'], $site) .'">Go</a>';
			$main			= $item['main'] ? 'Default' : 'none';
			$visible		= $item['visible'] ? 'visible' : 'hidden';
			//$copyLink		= '<button type="button" onClick="copyLink(\\\'' . \App\Core\Helpers::renderCMSLink($item['name'], $site) . '\\\')">Copy</button>';
			$link = \App\Core\Helpers::renderCMSLink($item['name'], $site);
			//$copyLink = "<button type=\"button\" onClick=\"copyLink(\\'${link}\\')\" ></button>";
			$copyLink = "<button type=\"button\" onClick=\"copyLink()\" >Copy</button>";

			$datas[] 		= "'".$item['id']."','".$item['name']."','".$item['category']."','".$item['creator']. "','" . $item['action'] . "','" . $main . "','". $visible . "','" . $buttonEdit . "','" . $buttonDelete . "','" . $buttonVisit. "','" . $copyLink."'";

		}
		$createPageBtn = ['label' => 'Create a page', 'link' => 'page/create'];

		$view = new View('list', 'back', $site);
		$view->assign("createButton", $createPageBtn);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the pages");
	}

	public function createPageAction($site){
		$pageObj = new Page($site['prefix']);
		$actionObj = new Action();
		$actions = $actionObj->findAll();
		$actionArr = [];
		if(!empty($actions)){
			foreach($actions as $action){
				$actionArr[$action['id']] = $action['name'];
			}
		}

		$form = $pageObj->formAddContent($actionArr);

		$view = new View('page', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a page");
        $view->assign('subDomain', $site['subDomain']);

		if(!empty($_POST) ) {
			$errors = [];
			[ "name" => $name, "action" => $action, "filters" => $filters, "visible" => $visible, "main" => $main ] = $_POST;
			if( $name ){
				if( !empty($action) && $action !== '0'){
					$pageObj->setAction($action);
				}

				$actionObj->setId($action);
				$check = $actionObj->findOne();
				if(!$check){
					return;
				}
				
				$pageObj->setName($name);
				$pageObj->setCreator(Security::getUser());
				$pageObj->setVisible($visible??null);
				$pageObj->setMain($main??null);

				if($filters){
					$contentAction = json_encode(array( $check['filters'] => $filters));
					$pageObj->setFilters(($contentAction));
				}
				$adding = $pageObj->save();

				if($main == true){
					$pageObj->updateAll(['main' => 'false'], ['main' => 'true'], ['id' => $pageObj->getLastId()]);
				}

				if($adding){
					$message ='Page successfully published!';
					$view->assign("message", $message);
					\App\Core\Helpers::customRedirect('/admin/pages?success', $site);
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
			header("Location: pages");
			exit();
		}

		$pageObj = new Page($site['prefix']);
		$pageObj->setId($_GET['id']??0);
		$page = $pageObj->findOne();
		if(!$page){
			header("Location: pages");
			exit();
		}

		$contentObj = new Content($site['prefix']);
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

		$categoryObj = new Category($site['prefix']);
		$category = $categoryObj->findAll();
		$categoryArr = array();
		$categoryArr[] = 'None';
		if(!empty($category)){
			foreach($category as $data){
				$categoryArr[$data['id']] = $data['name'];
			}
		}
		
		$pageArr 	= (array)$page;
		$contentArr = (array)$content;
		$contentArr = [ 'action' => $contentArr['method'] ];		
		$pageArr = array_merge((array)$page, $contentArr);

		$form = $pageObj->formEditContent($pageArr, $categoryArr, $actionArr, ($content['filter']));

		$view = new View('page', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a page");
        $view->assign('subDomain', $site['subDomain']);
		if(!empty($_POST) ) {
			try{
				[ "name" => $name, "category" => $category, "action" => $action, "filters" => $filters, "visible" => $visible, "main" => $main] = $_POST;
				if( $name ){
					$pageObj->setName($name);
					$pageObj->setCategory($category??null);
					$pageObj->setAction($action??null);
					$pageObj->setVisible($visible??null);
					$pageObj->setMain($main??null);

					$actionObj->setId($action);
					$check = $actionObj->findOne();
					if(!$check){
						return;
					}
					$contentAction = json_encode(array( $check['filters'] => $filters));
					$pageObj->setFilters(($contentAction));
					$adding = $pageObj->save();
					if($main == true){
						$pageObj->updateAll(['main' => 'false'], ['main' => 'true'], ['id' => $pageObj->getId()]);
					}
					if($adding){
						$message ='Page successfully updated!';
						$view->assign("message", $message);
						\App\Core\Helpers::customRedirect('/admin/pages?success', $site);
					}else{
						$errors = ["Error when updating this page"];
						$view->assign("errors", $errors);
					}
				}
			}catch(\Exception $e){

			}
		}
	}

	public function deletePageAction($site){
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('page not set');}
			$pageObj = new Page($site['prefix']);
			$pageObj->setId($_GET['id']??0);
			$page = $pageObj->findOne();

			if(!$page){ throw new \Exception('No page found'); }
			$check = $pageObj->delete();
			if(!$check){ throw new \Exception('Cannot delete this page');}
			\App\Core\Helpers::customRedirect('/admin/pages?success', $site);
		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/pages?error', $site);
		}
	}

}