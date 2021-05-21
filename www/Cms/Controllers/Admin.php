<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Core\View;

class Admin{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", $this->renderNavBar($site));
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
		$view->assign("navbar", $this->renderNavBar($site));
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

	public function managePagesAction($site){
		$pageObj = new Page(null, $site['prefix']);
		$pages = $pageObj->findAll();
		$pagesList = [];
		
		foreach($pages as $item){
			$pagesList[] = $pageObj->listFormalize($item);
		}
		$createPageBtn = '<a href="createpage"><button>Create</button></a>';

		$view = new View('admin.list', 'back');
		$view->assign("navbar", $this->renderNavBar($site));
		$view->assign("content", $createPageBtn);
		$view->assign("list", $pagesList);
		$view->assign('pageTitle', "Manage the pages");
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
		$view->assign("navbar", $this->renderNavBar($site));
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
		$view->assign("navbar", $this->renderNavBar($site));
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
		$view->assign("navbar", $this->renderNavBar($site));
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

	public function renderNavBar($site){
		$url = $site['subDomain'];
		$html = '<nav><ul>';
		$html .= "<li><a href='/site/${url}/admin/'>Dashboard</a></li>";
		$html .= "<li><a href='managepages'>Pages</a></li>";
		$html .= "<ul><li><a href='createpage'>Create</a></li></ul>";
		$html .= "<li><a href='managearticles'>Articles</a></li>";
		$html .= "<ul><li><a href='createarticle'>Create</a></li></ul>";
		$html .= "<li><a href='/'>Users</a></li>";
		$html .= "<li><a href='/'>Media library</a></li>";
		$html .= "<li><a href='/'>Roles</a></li>";
		$html .= "<li><a href='/'>Mailing</a></li>";
		$html .= "<li><a href='/'>Events</a></li>";
		$html .= "<li><a href='/'>Advanced</a></li>";
		$html .= "</ul></nav>";
		return $html;
	}

}