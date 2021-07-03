<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Post;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Comment;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

use App\Core\Security;

class PostController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back',  $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function createArticleAction($site){
		$postObj = new Post();

		$form = $postObj->formAddContent();
		$view = new View('admin.create', 'back',  $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add an article");

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content, "allowComment" => $allowComment ] = $_POST;
			if($title && $content){
				$insert = new Post();
				$insert->setTitle($title);
				$insert->setContent($content);
				$insert->setPublisher(Security::getUser());
				$insert->setAllowComment($allowComment);
				$insert->setPrefix($site['prefix']);
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
		$postObj->setPrefix($site['prefix']);
		$posts = $postObj->findAll();
		$fields = [ 'id', 'title', 'content', 'publisher', 'publication date', 'Edit', 'Delete' ];
		$datas = [];
		foreach($posts as $item){
			$userObj = new User();
			$userObj->setId($item['publisher']);
			$user = $userObj->findOne();

			$item['publisher'] = $user['firstname']??'None';
			$buttonEdit = '<a href=\"editArticle?id=' . $item['id'] . '\">Go</a>';
			$buttonDelete = '<a href=\"deleteArticle?id=' . $item['id'] . '\">Go</a>';
			//$item['content'] = 
			$datas[] = "\"" . $item['id'] . "\",\"" . $item['title'] . "\",\"" . $item['content'] . "\",\"" . $item['publisher'] .  "\",\"" . $item['publicationDate'] . "\",\"" . $buttonEdit . "\",\"" . $buttonDelete ."\"";
		}
		$createArticleBtn = ['label' => 'Create an article', 'link' => 'createarticle'];
		$view = new View('back/list', 'back',  $site);
		$view->assign("createButton", $createArticleBtn);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the articles");
	}

	public function editArticleAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'article not set ';
		}

		$contentObj = new Post();
		$contentObj->setPrefix($site['prefix']);
		$contentObj->setId($_GET['id']);
		$content = $contentObj->findOne();
		if(!$content){
			header("Location: managearticles");
			exit();
		}

		$view = new View('admin.create', 'back',  $site);

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $postContent, "allowComment" => $allowComment] = $_POST;
			if($title && $postContent){
				$contentObj->setTitle($title);
				$contentObj->setContent($postContent);
				$contentObj->setAllowComment($allowComment);
				$adding = $contentObj->save();
				if($adding){
					$message ='Article successfully updated!';
					$view->assign("message", $message);
				}else{
					$contentObj->setTitle(null);
					$contentObj->setContent(null);
					$contentObj->setAllowComment(null);
					$errors = ["Error when updating this article"];
					$view->assign("errors", $errors);
				}
				$content = $contentObj->findOne();
			}else{
				$errors = ["Missing required field(s)"];
			}
		}

		$form = $contentObj->formEditContent($content);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit an article");
		$view->assign('errors', $errors??[]);

	}

	public function deleteArticleAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'article not set ';
			header("Location: managearticles");
			exit();
		}

		$contentObj = new Post();
		$contentObj->setPrefix($site['prefix']);
		$contentObj->setId($_GET['id']);
		$content = $contentObj->findOne();
		if(!$content){
			header("Location: managearticles");
			exit();
		}
		$contentObj->delete();
		header("Location: managearticles");
		exit();
	}

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/
	public function renderList($site, $filter = null){
		$postObj = new Post();
        $postObj->setPrefix($site->getPrefix());
        $contents = $postObj->findAll();
        $html = "";
        if(!$contents || count($contents) === 0){
            $html .= 'No content found :/';
            return;
        }

        foreach($contents as $content){
            $postObj = new Post();
			$postObj->setTitle($content['title']);
			$postObj->setContent($content['content']);
			$postObj->setPublisher($content['publisher']);
			$postObj->setId($content['id']);
			$html .= $this->renderPostItem($postObj->returnData());
        }

		$view = new View('cms', 'front', $site);
		$view->assign('pageTitle', 'Posts');
		//$view->assign("navbar", NavbarBuilder::renderNavbar($site->returnData(), 'front'));
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('content', $html);
	}

	public function renderPostItem($content){
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
        
		$html = '<h2><a href="ent/post?id='. $id . '">' . $title . '</a></h2>';
		$html .= '<p id='. $publisher['id'] .' >By ' . $name . ' </p>';
		$html .= '<p>' . $content . '</p>';
		$html .= '<hr>';

        return $html;
	}

	//$site is an instance of Site
	public function renderPostAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			return 'article not set ';
		}
		$user = Security::getUser();
        $userObj = new User();

		$commentObj = new Comment();
		$commentObj->setPrefix($site->getPrefix());

		$postObj = new Post();
        $postObj->setPrefix($site->getPrefix());
		$postObj->setId($_GET['id']);
        $post = $postObj->findOne();
        if(!$post){
            return 'No content found :/';
        }
		
		/* Retrieve post author */
		if(!empty($post['publisher']))
        {
			$userObj->setId($post['publisher']);
        	$publisher = $userObj->findOne();
			$post['author'] = $publisher['firstname'] . " " . $publisher['lastname'];
		}else{
			$post['author'] = 'Unknown';
		}

		#if the admin allows the post to get commented
		if($post['allowComment'] === 1){
			$commentObj->setIdPost($_GET['id']);

			if(isset($_POST['message']) && !empty($_POST['message']) && $user)
			{
				$commentObj->setMessage($_POST['message']);
				$commentObj->setIdUser($user);
				$commentPublished = $commentObj->save();
				if(!$commentPublished){
					$errors[] = 'Your comment could not be published';
				} else {
					$commentObj->setMessage(null);
					$commentObj->setIdUser(null);
				}
			}
	
			$comments = $commentObj->findAll();
			if( $comments ){
				$commentsTmp = [];
				foreach($comments as $comment)
				{
					$userObj->setId($comment['idUser']);
					$commentAuthor = $userObj->findOne();
					$comment['author'] = $commentAuthor['firstname'] . ' ' . $commentAuthor['lastname'];
	
					$date = new \DateTime($comment['date']);
					$comment['date'] =  $date->format('d/m/y H:i:s');
					$commentsTmp[] = $comment;
				}
				$comments = $commentsTmp;
			} 
		}
		
		$errors = [];

		$view = new View('front/post', 'front',  $site);
		$view->assign('pageTitle', $post['title']);
		$view->assign("errors", $errors);
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('post', $post);
		$view->assign('canPostComment', !Security::getUser() == 0 );
		$view->assign('comments', $comments??[]);

	}

}