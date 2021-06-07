<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;
use App\Core\Token;

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
			$user->setEmail(htmlspecialchars($_POST['email']));
			$result = $user->findOne();
			if ( password_verify(htmlspecialchars($_POST['pwd']), $result['pwd']) && $result['isActive'] == 1){
				Secu::connect($result);
				echo Secu::isConnected();
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
				$user->save();
				$userFetch = $user->findOne();
				$mail = new MailToken();
				$mail->setUserId($userFetch["id"]);
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
		$user = new User();
		$user->setId(2);
		$user->setEmail("testAjaha");
		$user->save();
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