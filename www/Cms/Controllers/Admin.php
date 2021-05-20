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
		echo $site['prefix'];

		if(!empty($_POST) ) {
			[ "title" => $title, "content" => $content, "page" => $page ] = $_POST;
			if($title && $content && $page){
				echo 'We\'re gonna add this into the database <br>';
			}
			return;
		}


		$page = new Page(null, $site['prefix']);
		$pages = $page->findAll();
		print_r($pages);

		/*$content = new Content(null, null, null, null);
		$form = $content->formAddContent();
		$extraVars = ['pageTitle' => 'Add an article'];

		$view = new View('admin', 'back', $extraVars);
		$view->assign("form", $form);

		//$pageTitle = 'Add an article';
		$view->assign('pageTitle', "Add an article");*/




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