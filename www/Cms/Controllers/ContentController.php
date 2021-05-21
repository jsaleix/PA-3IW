<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;

class ContentController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function createArticleAction($site){
		$content = new Content(null, null, null, null);

		$page = new Page(null, $site['prefix']);
		$pages = $page->findAll();
		$pagesArr = array();
		foreach($pages as $data){
			$pagesArr[$data['id']] = $data['name'];
		}

		$form = $content->formAddContent($pagesArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add an article");

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content, "page" => $page ] = $_POST;
			if($title && $content && $page){
				$insert = new Content($title, $content, $page, 2);
				$insert->setTableName($site['prefix']);
				$adding = $insert->save();
				if($adding){
					$message ='Article successfully published!';
					$view->assign("message", $message);
				}else{
					$errors = ["Impossible d\'inserer l'article"];
					$view->assign("errors", $errors);
				}
			}
		}
	}

	public function manageArticlesAction($site){
		$contentObj = new content();
		$contentObj->setTableName($site['prefix']);
		$contents = $contentObj->findAll();
		$contentList = [];

		foreach($contents as $item){
			$pageObj = new Page(null, $site['prefix']);
			$pageObj->setId($item['page']);
			$page = $pageObj->findOne();
			$item['page'] = $page['name']??'None';

			$userObj = new User();
			$userObj->setId($item['publisher']);
			$user = $userObj->findOne();

			$item['publisher'] = ("by " . $user['firstname'])??'None';
			$contentList[] = $contentObj->listFormalize($item);
		}
		$createArticleBtn = '<a href="createarticle"><button>Create</button></a>';

		$view = new View('admin.list', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("content", $createArticleBtn);
		$view->assign("list", $contentList);
		$view->assign('pageTitle', "Manage the articles");
	}

	public function editArticleAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'article not set ';
		}

		$page = new Page(null, $site['prefix']);
		$pages = $page->findAll();
		$pagesArr = array();
		foreach($pages as $data){
			$pagesArr[$data['id']] = $data['name'];
		}

		$contentObj = new Content(null);
		$contentObj->setTableName($site['prefix']);
		$contentObj->setId($_GET['id']);
		$content = $contentObj->findOne();
		if(!$content){
			header("Location: managearticles");
		}
		$pagesArr = array();
		foreach($pages as $data){
			$pagesArr[$data['id']] = $data['name'];
		}

		$form = $contentObj->formEditContent((array)$content, $pagesArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit an article");

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content, "page" => $page ] = $_POST;
			if($title && $content && $page){
				/*$insert = new Content($title, $content, $page, 2);
				$insert->setTableName($site['prefix']);*/
				$contentObj->setTitle($title);
				$contentObj->setContent($content);
				$contentObj->setPage($page);
				$adding = $contentObj->save();
				if($adding){
					$message ='Article successfully updated!';
					$view->assign("message", $message);
				}else{
					$errors = ["Error when updating this article"];
					$view->assign("errors", $errors);
				}
			}
		}
	}

}