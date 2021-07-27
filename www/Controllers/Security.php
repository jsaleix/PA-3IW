<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\FileUploader;

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
			$user->setEmail(htmlspecialchars($_POST['email']));
			$result = $user->findOne();
			if ( password_verify(htmlspecialchars($_POST['pwd']), $result['pwd'])){
				if( $result['isActive'] == 0){
					$errors = ["Vous devez activer votre compte grâce au mail que nous vous avons envoyé avant de vous connecter"];
					$view->assign("errors", $errors);
				} else {
					Secu::connect($result);
					header('Location: '.DOMAIN);
					exit();
				}
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
				$user->setRole(1);
				$user->setPwd( password_hash(htmlspecialchars($_POST["pwd"]), PASSWORD_BCRYPT) );
				$res = $user->save();
				if( !$res){
					$errors[] = "This email is already taken try something else";
					$view->assign("errors", $errors);
					$view->assign("form", $form);
					return;
				}
				$userFetch = $user->findOne();
				$mail = new MailToken();
				$mail->setUserId($userFetch["id"]);
				$mail->setExpiresDate(new \DateTime('now'));
				$mail->setToken(bin2hex(random_bytes(128)));
				$mail->save();

				$userId = $user->findOne();
				FileUploader::createUserDirs($userId['id']);
				
				$mail->sendConfirmationMail($user->getEmail());
				
				header('Location: '.DOMAIN);
				exit();
			}else{
				$view->assign("errors", $errors);
			}

		}
		$view->assign("form", $form);
	}

	public function logoutAction(){
		Secu::disconnect();
		header('Location: '.DOMAIN);
		exit();
	}

	public function mailconfirmAction(){
		$token = new MailToken();
		$token->setToken($_GET['token']);
		$token = $token->findOne();
		$user = new User();
		$user->setId($token['userId']);
		$result = $user->findOne();
		$now = new \DateTime('now');
		$tokenDate = new \DateTime($token["expiresDate"]);
		$interval = $now->diff($tokenDate);
		if( $interval->format('%R') != "+"){
			echo "Token expiré un nouveau vous a été envoyé";
			$mail = new MailToken();
			$mail->setUserId($result["id"]);
			$mail->setExpiresDate(new \DateTime('now'));
			$mail->setToken(bin2hex(random_bytes(128)));
			$mail->save();
			$mail->sendConfirmationMail($result["email"]);
			return;
		}
		if( $result["isActive"] == 1 ){
			echo "already active";
			return;
		}
		$user->setIsActive(1);
		if( $user->save())
			echo "Compte activé";
	}

}