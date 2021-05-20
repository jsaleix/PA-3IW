<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Core\View;

class Admin{


	public function defaultAction(){
		echo 'Default admin action on CMS <br>';
		echo 'We\'re gonna assume that you are the site owner <br>'; 
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

		$view = new View('admin', 'back');
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
				echo 'We\'re gonna add this into the database <br>';
			}
		}



		/*$view = new View("login");

		$form = $user->formLogin();

		if(!empty($_POST) && !empty($_POST['email'])){
			$user->setEmail(htmlspecialchars($_POST['email']));
			$result = $user->findOne();
			if ( password_verify(htmlspecialchars($_POST['pwd']), $result['pwd']))
				print_r($result);
			else{
				$errors = ["Utilisateur non trouvé"];
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);*/

	}


}