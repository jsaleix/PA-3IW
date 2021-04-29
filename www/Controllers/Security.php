<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;


use App\Models\User;
use App\Models\MailToken;

use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../vendor/autoload.php';

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
				$user->setFirstname(htmlspecialchars($_POST["firstname"]));
				$user->setLastname(htmlspecialchars($_POST["lastname"]));
				$user->setEmail(htmlspecialchars($_POST["email"]));
				$user->setPwd( password_hash(htmlspecialchars($_POST["pwd"]), PASSWORD_BCRYPT) );
				$userId = $user->save();
				$mail = new MailToken();
				$mail->setUserId($userId);
				$mail->setExpiresDate(new \DateTime('now'));
				$mail->setToken(bin2hex(random_bytes(128)));
				$mail->save();
				$mail->sendConfirmationMail($user->getEmail());
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