<?php

namespace CMS\Controller;
use App\Models\User;
use App\Core\FormValidator;

use CMS\Models\Medium;
use CMS\Models\Post;
use CMS\Models\Comment;
use CMS\Models\Post_Medium_Association as PMAssoc;

use CMS\Core\CMSView as View;
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
		$postObj = new Post($site->getPrefix());

		$form = $postObj->formAddContent();
		$view = new View('post.association', 'back',  $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add an article");

		if(!empty($_POST) ) {
			$errors = [];
			$errors = FormValidator::check($form, $_POST);
			$_POST = array_merge($_POST, [ "publisher" => Security::getUser() ] );
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			$pdoResult = $postObj->populate($_POST, TRUE);
			if( $pdoResult ){
				$message = "Article successfully published!";
				$view->assign("message", $message);
				\App\Core\Helpers::customRedirect('/admin/articles?success', $site);
			} else {
				$errors[] = "Cannot insert article";
				$view->assign("errors", $errors);
			}
		}
	}

	public function manageArticlesAction($site){
		$postObj = new Post($site->getPrefix());
		$posts = $postObj->findAll();
		$fields = [ 'id', 'title', 'content', 'publisher', 'publication date', 'Edit', 'Delete' ];
		$datas = [];
		foreach($posts as $item){
			$userObj = new User();
			$userObj->setId($item['publisher']);
			$user = $userObj->findOne();

			$item['publisher'] = $user['firstname']??'None';
			$buttonEdit = '<a href=\"article/edit?id=' . $item['id'] . '\">Go</a>';
			$buttonDelete = '<a href=\"article/delete?id=' . $item['id'] . '\">Go</a>';
			$datas[] = "\"" . $item['id'] . "\",\"" . $item['title'] . "\",\"" . $item['content'] . "\",\"" . $item['publisher'] .  "\",\"" . $item['publicationDate'] . "\",\"" . $buttonEdit . "\",\"" . $buttonDelete ."\"";
		}
		$createArticleBtn = ['label' => 'Create an article', 'link' => 'article/create'];
		$view = new View('list', 'back',  $site);
		$view->assign("createButton", $createArticleBtn);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the articles");
	}

	public function editArticleAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'article not set ';
		}

		$contentObj = new Post($site->getPrefix());
		$contentObj->setId($_GET['id']);
		$content = $contentObj->findOne();
		if(!$content){
			header("Location: articles");
			exit();
		}
		$view = new View('post.association', 'back',  $site);
		$form = $contentObj->formEditContent($content);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit an article");
		$view->assign('errors', $errors??[]);

		$contentObj->findOne(TRUE);
		$PMAObj = new PMAssoc($site->getPrefix());
		$PMAObj->setPost($contentObj->getId());
		$PMAS = $PMAObj->findAll();
		$fields = ['name', 'image', 'Remove'];
		$datas = [];
		$mediumAssociated = [];
		if($PMAS){
			foreach($PMAS as $item){
				$mediumObj = new Medium($site->getPrefix());
				$mediumObj->setId($item['medium']);
				$mediumObj->findOne(TRUE);
				$mediumAssociated [] = $mediumObj->getId();
				$img = "<img src=".DOMAIN."/".$mediumObj->getImage()." width=100 height=80/>";
				$buttonDelete = "<a href=".\App\Core\Helpers::renderCMSLink("admin/medium/assoc/remove?id=".$item['id'], $site).">Go</a>";
				$formalized = "'".$mediumObj->getName()."','".$img."','".$buttonDelete."'";
				$datas[] = $formalized;
			}
			$lists[] = array( "title" => "Media on post", "datas" => $datas, "id" => "owned_media", "fields" => $fields );
		}
		$mediumObj = new Medium($site->getPrefix());
		$mediums = $mediumObj->findAll();
		$fields = ['name', 'image', 'Add'];
		$datas = [];
		if($mediums){
			foreach($mediums as $item){
				if( !in_array($item['id'], $mediumAssociated) ){
					$img = "<img src=".DOMAIN."/".$item['image']." width=100 height=80/>";
					$buttonAdd = "<a href=".\App\Core\Helpers::renderCMSLink("admin/medium/assoc/create?medium=".$item['id']."&post=".$contentObj->getId(), $site).">Go</a>";
					$formalized = "'".$item['name']."','".$img."','".$buttonAdd."'";
					$datas[] = $formalized;
				}
			}
		}
		$lists[] = array( "title" => "Media availables", "datas" => $datas, "id" => "available_media", "fields" => $fields );
		$view->assign("lists", $lists);

		if(!empty($_POST) ) {
			$errors = [];
			$errors = FormValidator::check($form, $_POST);
			$_POST = array_merge($_POST, [ "publisher" => Security::getUser() ] );
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			$pdoResult = $contentObj->populate($_POST, TRUE);
			if( $pdoResult ){
				\App\Core\Helpers::customRedirect('/admin/articles?success', $site);
			} else {
				$errors[] = "Cannot insert article";
				$view->assign("errors", $errors);
			}
		}
	}

	public function deleteArticleAction($site){
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('article not set');}
			$contentObj = new Post($site->getPrefix());
			$contentObj->setId($_GET['id']);
			$content = $contentObj->findOne();
			if(!$content){ throw new \Exception('No content found');}
			$check = $contentObj->delete();
			if(!$check){ throw new \Exception('Cannot delete this article');}
			\App\Core\Helpers::customRedirect('/admin/articles', $site);
		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/articles?error', $site);
		}
	}

	/*
	* Front vizualization
	*/
	public function renderList($site, $filter = null){
		$postObj = new Post($site->getPrefix());
        $posts = $postObj->findAll();
		$errors = [];

        if(!$posts || count($posts) === 0){
			array_push($errors, "No result");
            return;
        }
		
		$tmp_posts = [];

		foreach ($posts as $post){
			$publisher = new User();
			$publisher->setId($post['publisher']);
			$publisher = $publisher->findOne();

			array_push($tmp_posts, ["post" => $post, "publisher" => $publisher]);
		}
		
		$view = new View('posts', 'front', $site);
		$view->assign('pageTitle', 'Posts');
		$view->assign('posts', $tmp_posts);
	}

	public function renderPostAction($site, $filter = null){
		$view = new View('post', 'front',  $site);
		//Checks if the method is called as an action of the site or as an entity
		try{
			if(!empty($filter)){
				$filter = json_decode($filter, true);
				if(isset($filter['post'])){
					$postId = $filter['post'];
				}else{
                    throw new \Exception('Filter is not set');
				}
			}else if(isset($_GET['id']) && !empty($_GET['id']) ){
				$postId = $_GET['id'];
			}else{
				throw new \Exception('article is not set');
			}

			$errors = [];
			$user = Security::getUser();

			$userObj 	= new User();
			$commentObj = new Comment($site->getPrefix());

			$postObj = new Post($site->getPrefix());
			$postObj->setId($postId);
			$post = $postObj->findOne();
			if(!$post){
				throw new \Exception('Post not found');
			}
		}catch(\Exception $e){
			$view->assign('notFound', true);
			$view->assign('pageTitle', 'Not found');
			return 'No content found :/';	
		}
		
		// Retrieve post author
		if(!empty($post['publisher']))
        {
			$userObj->setId($post['publisher']);
        	$publisher = $userObj->findOne();
			$post['author'] = $publisher['firstname'] . " " . $publisher['lastname'];
		}else{
			$post['author'] = 'Unknown';
		}

		$PMAObj = new PMAssoc($site->getPrefix());
		$PMAObj->setPost($post['id']);
		$PMAS = $PMAObj->findAll();
		$medias = [];
		if($PMAS){
			foreach($PMAS as $item){
				$mediumObj = new Medium($site->getPrefix());
				$mediumObj->setId($item['medium']);
				$medias[] = $mediumObj->findOne();
			}
		}

		//if the admin allows the post to get comments
		if($post['allowComment'] == 1){
			$commentObj->setIdPost($postId);
			$form = $commentObj->form();
			if($_POST)
			{
				if($user)
				{
					try{
						$errors = FormValidator::check($form, $_POST); //CHECK AND SANATIZE FORM
						if( count($errors) > 0){
							throw new \Exception('Invalid data');
						}
	
						$commentObj->setIdUser($user);
						$commentPublished = $commentObj->populate($_POST, TRUE);
						if(!$commentPublished){
							$errors[] = 'Your comment could not be published';
						}
	
						$commentObj->setMessage(null);
						$commentObj->setIdUser(null);
						
					}catch(\Exception $e){}
				}else{
					$errors[] = 'You must be logged in to comment a post';
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
					$comment['date'] =  $date->format('d/m/y \a\t H:i');
					if($userObj->getId() == Security::getUser()){
						$comment['delete'] = TRUE;
					}
					$commentsTmp[] = $comment;
				}
				$comments = $commentsTmp;
			} 
		}
		
		$view->assign('pageTitle', $post['title']);
		$view->assign("errors", $errors);
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('post', $post);
		$view->assign('canPostComment', $user );
		if($post['allowComment'] && $user){
			$view->assign('commentForm', $form );
		}
		$view->assign('medias', $medias);
		$view->assign('comments', $comments??[]);

	}

}