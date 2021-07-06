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
		$postObj = new Post($site['prefix']);

		$form = $postObj->formAddContent();
		$view = new View('create', 'back',  $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add an article");

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content, "allowComment" => $allowComment ] = $_POST;
			if($title && $content){
				$insert = new Post($site['prefix']);
				$insert->setTitle($title);
				$insert->setContent($content);
				$insert->setPublisher(Security::getUser());
				$insert->setAllowComment($allowComment);
				$adding = $insert->save();
				if($adding){
					$message ='Article successfully published!';
					$view->assign("message", $message);
					\App\Core\Helpers::customRedirect('/admin/articles?success', $site);
				}else{
					$errors = ["Impossible d\'inserer l'article"];
					$view->assign("errors", $errors);
				}
			}
		}


	}

	public function manageArticlesAction($site){
		$postObj = new Post($site['prefix']);
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

		$contentObj = new Post($site['prefix']);
		$contentObj->setId($_GET['id']);
		$content = $contentObj->findOne();
		if(!$content){
			header("Location: articles");
			exit();
		}

		$view = new View('create', 'back',  $site);

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
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('article not set');}
			$contentObj = new Post($site['prefix']);
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
	* returns html for pageRenderer
	*/
	public function renderList($site, $filter = null){
		$postObj = new Post($site->getPrefix());
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
		
		$html = '<div class="article-display col-8 col-md-10 col-sm-11">';
		$html .= '<h2><a href="ent/post?id='. $id . '">' . $title . '</a></h2>';
		$html .= '<p>' . $content . '</p>';
		$html .= '<br/>';
		$html .= '<p id='. $publisher['id'] .' >Par <b>' . $name . '</b> le <span>01/01</span> à <span>10h11</span> </p>';
		$html .= '<hr/>';
		$html .= "</div>";



        return $html;
	}

	//$site is an instance of Site
	public function renderPostAction($site, $filter = null){
		if(!empty($filter)){
            $filter = json_decode($filter, true);
            if(isset($filter['post'])){
                $postId = $filter['post'];
            }else{
                return;
            }
		}else if(isset($_GET['id']) && !empty($_GET['id']) ){
			$postId = $_GET['id'];
		}else{
			return 'article not set ';
		}

		$user = Security::getUser();
        $userObj = new User();

		$commentObj = new Comment($site->getPrefix());

		$postObj = new Post($site->getPrefix());
		$postObj->setId($postId);
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
			$commentObj->setIdPost($postId);

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

		$view = new View('post', 'front',  $site);
		$view->assign('pageTitle', $post['title']);
		$view->assign("errors", $errors);
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('post', $post);
		$view->assign('canPostComment', !Security::getUser() == 0 );
		$view->assign('comments', $comments??[]);

	}

}