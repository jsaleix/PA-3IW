<?php

namespace App\Controller;

use App\Core\View;
use App\Core\FormValidator;
use App\Core\FileUploader;
use App\Core\Helpers;

use App\Models\User;
use App\Models\Role;
use App\Models\Site;

class Admin{


	public function defaultAction(){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('back/default', 'admin');
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

    public function displaySitesAction(){
        $userObj = new User();
        $siteObj = new Site();
		$sites = $siteObj->findAll();
		$fields = [ 'id', 'version', 'name', 'creator', 'subDomain', 'creation date', 'prefix', 'type', 'visit','edit' ];
		$datas = [];

		if($sites){
			foreach($sites as $item){
                $userObj->setId($item['creator']);
                $creator = $userObj->findOne();
				$visitBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$editBtn = '<a href="site?id=' . $item['id'] . '">Edit</a>';
                $creatorBtn = '<a href="user?id=' .  $item['creator'] . '">' . $creator['firstname'] . ' ' . $creator['lastname']. '</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . 0.0 . "','" . $item['name'] . "','" .$creatorBtn .  "','" . $item['subDomain'] . "','" . $item['creationDate'] . "','". $item['prefix'] . "','". $item['type'] . "','" . $visitBtn . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
		}
		//$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('back/list', 'admin');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the sites");
	}

	public function displaySiteAction(){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			header("Location:" . DOMAIN . '/admin/sites');
			exit();
		}
		$siteObj = new Site();
		$siteObj->setId($_GET['id']);
		$site = $siteObj->findOne();
		if(!$site){
			header("Location:" . DOMAIN . '/admin/sites');
			exit();
		}
		$form = $siteObj->formEdit($site);

		$view = new View('back/form', 'admin');
		$view->assign('pageTitle', "Manage a site");
		$view->assign("form", $form);
	}

	public function displayUserAction(){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			header("Location:" . DOMAIN . '/admin/users');
			exit();
		}
		$userObj = new User();
		$userObj->setId($_GET['id']);
		$user = $userObj->findOne(TRUE);
		if(!$user){
			header("Location:" . DOMAIN . '/admin/users');
			exit();
		}
		$siteObj = new Site();
		$view = new View('back/editUser', 'admin');

		$roleObj = new Role();
		$roles = $roleObj->findAll();
		$rolesArr = [];
		if($roles){
			foreach($roles as $item){
				$rolesArr[$item['id']] = $item['name'];
			}
		}
		$form = $userObj->formAdminEdit($rolesArr);

		##########
		/*update process here */
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
					$form = $userObj->formAdminEdit($rolesArr);

				} else {
					$message = "Cannot update your profile";
					$view->assign("alert", Helpers::displayAlert("error", $message, 3500));
				}
			}catch(\Exception $e){
				//echo $e->getMessage();
				$errors[] = $e->getMessage();
				$view->assign('errors', $errors);
			}
			
		}
		##########

		$siteObj->setCreator($_GET['id']);
		$sites = $siteObj->findAll();

		if($sites){
			$fields = [ 'img', 'version', 'name', 'creation date', 'prefix', 'visit','edit' ];
			$datas = [];
			foreach($sites as $item){
				$visitBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$editBtn = '<a href="site?id=' . $item['id'] . '">Edit</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$item['creationDate'] = (new \DateTime($item['creationDate']))->format('d/m/y H:i:s');
				$formalized = "'" . $img . "','" . 0.0 . "','" . $item['name'] . "','" . $item['creationDate'] . "','" . $item['prefix'] . "','" . $visitBtn . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
			$view->assign("fields", $fields);
			$view->assign("datas", $datas);
			$view->assign("list", true);
		}

		$view->assign('pageTitle', "Manage a site");
		$view->assign("form", $form);

	}

	public function displayUsersAction()
	{
        $userObj = new User();
		$users = $userObj->findAll();
		$fields = [ 'avatar', 'id', 'name', 'mail', 'join date', 'role', 'see' ];
		$datas = [];

		if($users){
			foreach($users as $item){
				$name = $item['firstname'] . ' ' . $item['firstname'];
				$img = '<img src=' . DOMAIN . '/' . $item['avatar'] . ' width=100 height=80/>';
				$editBtn = '<a href="user?id=' . $item['id'] . '">Go</a>';
				if($item['role'] > 0){
					$role = new Role();
					$role->setId($item['role']);
					$role->findOne(TRUE);
					$item['role'] = $role->getName();
				}else{
					$item['role'] = 'none';
				}

				$formalized = "'" . $img . "','" . $item['id'] . "','" . $name . "','" .$item['email'] .  "','" . $item['joinDate'] . "','" . $item['role']  . "','". $editBtn . "'";
				$datas[] = $formalized;
			}
		}
		//$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('back/list', 'admin');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the users");
	}

	public function displayRolesAction(){
		$roleObj = new Role();
		$roles = $roleObj->findAll();
		$fields = [ 'name', 'description', 'icon', 'is admin', 'edit'];
		$datas = [];

		if($roles){
			foreach($roles as $item){
				$icon 			= '<img src=' . DOMAIN . '/' . $item['icon'] . ' width=100 height=80/>';
				$editBtn 		= '<a href="role?id=' . $item['id'] . '">Go</a>';
				$description 	= strlen($item['description']) != 0 ? $item['description'] : 'No description yet';
				$isAdmin		= $item['isAdmin'] ? 'Yes' : 'No';

				$formalized 	= "'" . $item['name'] . "','" . $description . "','" . $icon . "','" .  $isAdmin ."','" . $editBtn ."'";
				$datas[] = $formalized;
			}
		}
		$createBtn = '<a href="/admin/role/create"><button>New</button></a>';

		$view = new View('back/list', 'admin');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the roles");
		$view->assign("button", $createBtn);
	}

	public function createRoleAction(){
		$roleObj = new Role();

		$form = $roleObj->formCreate();

		$view = new View('back/form', 'admin');
		$view->assign('pageTitle', "Create a role");
		$view->assign("form", $form);
		if(!empty($_POST))
		{
			$errors = [];
			try{

				$data = array_merge($_POST, $_FILES);
				$errors = FormValidator::check($form, $data);
				if( count($errors) > 0){
					$view->assign("errors", $errors);
					throw new \Exception("Invalid form");
				}
				if(isset($data['description']) && strlen($data['description']) == 0){
					$data['description'] = 'IS NULL';
				}

				$icon = $_FILES['icon'];
				if(empty($icon['name']) || strlen($icon['name']) == 0){
					throw new \Exception("icon missing");
				}
				$imgDir = "/uploads/main/icons/roles/";
				$imgName = (new \DateTime())->format("Ymd_Hisu");
				$isUploaded = FileUploader::uploadImage($icon, $imgName, $imgDir);
				if( !$isUploaded ){ 
					$errors[] = 'Invalid or missing image';
					throw new \Exception('Invalid or missing image'); 
				}
				$data["icon"] = $isUploaded;

				$saving = $roleObj->populate($data, TRUE);
				if(!$saving){
					throw new \Exception("Could not create the role");
				}else{
					\App\Core\Helpers::customRedirect('/admin/roles?success');
				}
			
			}catch(\Exception $e){
				$errors[] = $e->getMessage();
				$view->assign("errors", $errors);
				$message = "Could not create this role!";
				$view->assign("message", $message);
				return;
			}
		}

	}

	public function editRoleAction(){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			\App\Core\Helpers::customRedirect('/admin/roles?error');
		}
		$roleObj = new Role();
		$roleObj->setId($_GET['id']);
		$role = $roleObj->findOne(TRUE);
		if(!$role){
			\App\Core\Helpers::customRedirect('/admin/roles?error');
		}

		$form = $roleObj->formEdit();

		$view = new View('back/form', 'admin');
		$view->assign('pageTitle', "Edit a role");
		$view->assign("form", $form);

		if(!empty($_POST)){
			try{
				$errors = [];

				$data = array_merge($_POST, $_FILES);
				$errors = FormValidator::check($form, $data);
				if( count($errors) > 0){
					$view->assign("errors", $errors);
					throw new \Exception("Could not save the changes");
				}

				if(isset($data['description']) && strlen($data['description']) == 0){
					$data['description'] = 'IS NULL';
				}

				$icon = $_FILES['icon'];
				if(!empty($icon['name']) && strlen($icon['name']) > 0)
				{
					$imgDir = "/uploads/main/icons/roles/";
					$imgName = (new \DateTime())->format("Ymd_Hisu");
					$isUploaded = FileUploader::uploadImage($_FILES["icon"], $imgName, $imgDir);
					if( !$isUploaded ){ 
						$errors[] = 'Invalid or missing image';
						throw new \Exception('Invalid or missing image'); 
					}
					$data["icon"] = $isUploaded;
				}else{
					$data['icon'] = null;
				}

				$saving = $roleObj->populate($data, TRUE);

				if(!$saving){
					throw new \Exception("Could not save the changes");
				}else{
					\App\Core\Helpers::customRedirect('/admin/roles?success');
				}
			}catch(\Exception $e){
				$message = "Could not update this role!";
				$view->assign("message", $message);
				$e->getMessage();
				return;
			}
		}

	}

}