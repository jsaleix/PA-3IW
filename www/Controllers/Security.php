<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;

use App\Models\User;
use App\Models\MailToken;

class Security{


	public function defaultAction(){
		echo "Controller security action default";
	}

	public function loginAction(){
		$user = new User();
		$view = new View("login");

		$form = $user->formLogin();

		if(!empty($_POST) && !empty($_POST['email'])){
			$doesUserExist = true /*$user->exists("email", $_POST['email'], "*")*/;

			if(!empty($doesUserExist) && !is_null($doesUserExist)){
				echo var_dump($doesUserExist);
			}else{
				$errors = ["Utilisateur non trouvé"];
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);

	}

	public function registerAction(){

		$user = new User();
		$view = new View("register");

		$form = $user->formRegister();

		if(!empty($_POST)){
			$errors = FormValidator::check($form, $_POST);

			if(empty($errors)){
				$user->setFirstname($_POST["firstname"]);
				$user->setLastname($_POST["lastname"]);
				$user->setEmail($_POST["email"]);
				$user->setPwd($_POST["pwd"]);
				$userId = $user->save();
				echo "AH";
				$mail = new MailToken();
				$mail->setUserId($userId);
				$mail->setExpiresDate(new \DateTime('now'));
				$mail->setToken(bin2hex(random_bytes(128)));
				$mail->save();

			}else{
				$view->assign("errors", $errors);
			}

		}
		$view->assign("form", $form);

	}

	public function logoutAction(){

		$security = new Secu();
		if($security->isConnected()){
			echo "OK";
		}else{
			echo "NOK";
		}
		
	}


	public function updateAction(){
		echo $_GET["yo"];
		$user = new User();
		$user->setId(1);
		$user->setEmail("testAjaha");
		$user->save();
	}
	

}