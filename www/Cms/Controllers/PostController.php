<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Post;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;

class PostController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function createArticleAction($site){
		$postObj = new Post(null, null, null, null);

		$page = new Page(null, $site['prefix']);
		$pages = $page->findAll();
		$pagesArr = array();
		foreach($pages as $data){
			$pagesArr[$data['id']] = $data['name'];
		}

		$form = $postObj->formAddContent($pagesArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add an article");

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content ] = $_POST;
			if($title && $content){
				$insert = new Post($title, $content, 2);
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
		$postObj = new Post();
		$postObj->setTableName($site['prefix']);
		$posts = $postObj->findAll();
		$postList = [];

		foreach($posts as $item){
			$userObj = new User();
			$userObj->setId($item['publisher']);
			$user = $userObj->findOne();

			$item['publisher'] = ("by " . $user['firstname'])??'None';
			$postList[] = $postObj->listFormalize($item);
		}
		$createArticleBtn = '<a href="createarticle"><button>Create</button></a>';

		$view = new View('admin.list', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site));
		$view->assign("content", $createArticleBtn);
		$view->assign("list", $postList);
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

		$contentObj = new Post();
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
			[ "title" => $title, "content" => $content] = $_POST;
			if($title && $content ){
				/*$insert = new Content($title, $content, $page, 2);
				$insert->setTableName($site['prefix']);*/
				$contentObj->setTitle($title);
				$contentObj->setContent($content);
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

	/*
	* Front vizualization
	*/
	public function render($site, $filter = null){
		$contentObj = new Post(null, null, null, null);
        $contentObj->setTableName($site->getPrefix());
        $contents = $contentObj->findAll();
        
        if(!$contents || count($contents) === 0){
            echo 'No content found :/';
            return;
        }

        foreach($contents as $content){
            $contentObj = new Post($content['title'], $content['content'], $content['publisher']);
            $this->renderPost($contentObj->returnData());
        }
	}

	public function renderPost($content){
        $publisherData = new User();
        extract($content);
		if(!empty($publisher))
        {
			$publisherData->setId($publisher);
        	$publisher = $publisherData->findOne();
			$name = $publisher['firstname'] . " " . $publisher['lastname'];
		}else{
			$name = 'Unknown';
		}
        
		$html = '<h2>' . $title . '</h2>';
		$html .= '<p id='. $publisher['id'] .' >By ' . $name . ' </p>';
		$html .= '<p>' . $content . '</p>';
		$html .= '<hr>';

        echo $html;
	}

}