<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\FileUploader;

use App\Models\User;
use App\Models\Site;
use App\Models\Whitelist;

use App\Core\Helpers; 

class Account{


	public function defaultAction(){
        $userObj = new User();
		$userObj->setId(Secu::getUser());
		$userObj->findOne(TRUE);

		$view = new View('front/account', 'front');

		$form = $userObj->formEdit();

		if(!empty($_POST)){
			$errors = [];

			$data = array_merge($_POST, $_FILES);
			try{
				$errors = FormValidator::check($form, $data);
				if( count($errors) > 0)
				{
					$view->assign("errors", $errors);
					throw new \Exception('Invalid form');
				}

				if($data['email'] !== $userObj->getEmail()){
					$newUser = new User();
					$newUser->setEmail(htmlspecialchars($data['email']));
					if($newUser->findOne()){
						$message = "Mail already taken";
						$view->assign("alert", Helpers::displayAlert("success",$message,3500));

						throw new \Exception('Invalid mail');
					}
				}

				if(isset($_FILES['avatar']))
				{
					$imgDir = "/uploads/users/" . $userObj->getId() . '/';
					$imgName = 'avatar';
					$isUploaded = FileUploader::uploadImage($_FILES["avatar"], $imgName, $imgDir);
					$data['avatar'] = $isUploaded ? $isUploaded : null;
				}

				if($userObj->populate($data, TRUE)){
					$message = "Profile successfully updated!";
					$view->assign("alert", Helpers::displayAlert("success",$message,3500));
					$form = $userObj->formEdit();

				} else {
					$message = "Cannot update your profile";
					$view->assign("alert", Helpers::displayAlert("error", $message, 3500));
				}
			}catch(\Exception $e){
				//echo $e->getMessage();
			}
			
		}

		$view->assign("form", $form);
	}

	public function updatePasswordAction(){
        $userObj = new User();
		$userObj->setId(Secu::getUser());
		$userObj->findOne(TRUE);

		$view = new View('front/account.pwd', 'front');
		$form = $userObj->formPwd();
		$view->assign("form", $form);

		if(!empty($_POST)){
			try{
				$errors = [];
				$errors = FormValidator::check($form, $_POST);
				if( count($errors) > 0)
				{
					$view->assign("errors", $errors);
					throw new \Exception('Invalid form');
				}
				if($_POST['pwd'] != $_POST['pwdConfirm']){
					$errors[] = 'Passwords do not match';
					throw new \Exception('Passwords do no match');
				}

				if( !password_verify(htmlspecialchars($_POST['oldPwd']), $userObj->getPwd()))
				{
					$errors[] = 'Wrong password';
					throw new \Exception('Wrong password');
				}

				$newPwd = password_hash(htmlspecialchars($_POST["pwd"]), PASSWORD_BCRYPT);
				$userObj->setPwd($newPwd);
				if($userObj->save()){
					$message = "Password successfully updated!";
					$view->assign("alert", Helpers::displayAlert("success",$message,3500));

				} else {
					$message = "Cannot update your profile";
					$view->assign("alert", Helpers::displayAlert("error",$message,3500));

				}
			}catch(\Exception $e){
				$view->assign("errors", $errors);
				//echo $e->getMessage();
			}
		}


	}

	public function mysitesAction(){		
		$siteObj = new Site();
		$userObj = new User();
		$lists = [];

		//Fetching all the sites owned by the user
		$siteObj->setCreator(Secu::getUser());
		$ownedSites = $siteObj->findAll();

		if($ownedSites){
			$datas = [];
			foreach($ownedSites as $item){
                $userObj->setId($item['creator']);
                $creator = $userObj->findOne();
				$datas[] = $item;
			}
			$lists[] = array( "title" => "My Sites", "datas" => $datas);
		}

		//Fetching all the sites on which the user is whitelisted on
		$siteObj->setCreator(null);
		$wlistObj = new Whitelist();
		$wlistObj->setIdUser(Secu::getUser());
		$wlists = $wlistObj->findAll();
		$sites = [];
		if($wlists){
			foreach($wlists as $wlist){
				$siteObj->setId($wlist["idSite"]);
				$sites[] = $siteObj->findOne();
			}
		}
		$fields = [ 'id', 'version', 'name', 'creator', 'subDomain', 'creation date', 'type', 'visit','edit' ];
		$datas = [];

		if($sites == true){
			foreach($sites as $item){
                $userObj->setId($item['creator']);
                $creator = $userObj->findOne();
				$item['creator'] = $creator;
				$datas[] = $item;
			}
		}
		$lists[] = array( "title" => "Sites you are whitelisted on", "datas" => $datas);
		$view = new View('front/list.account', 'front');
		$view->assign("lists", $lists);
		$view->assign('pageTitle', "Manage the sites");
	}


	public function searchUsersAction(){
		$userParam = $_GET['param']??'';
		$usersArr = [];
		$msg = "";
		try{
			if(!$userParam){
				throw new \Exception('No parameters');
			};

			$userObj = new User();

			$userObj->setEmail($userParam);
			$userObj->setFirstname($userParam);
			$userObj->setLastName($userParam);

			$users = $userObj->findAllLike('Id');

			if(!$users){ 
				throw new \Exception('No result');
			}else{
				$code = 200;
				foreach($users as $user){
					$usersArr[] = array(
						'id' => $user['id'],
						'name' => $user['firstname'] . ' '. $user['lastname'] ,
						'image' => DOMAIN . '/' . $user['avatar'],
					);
				}
			}
		}catch(\Exception $e){
			$code = 404;
			$msg = $e->getMessage();
		}

        echo json_encode(array('msg' => $msg, 'code' => $code, 'users' => $usersArr));
		//http_response_code($code);
		exit();
	} 


}