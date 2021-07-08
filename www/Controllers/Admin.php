<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;
use App\Core\FileUploader;

use App\Models\User;
use App\Models\Role;
use App\Models\Site;
use App\Models\Whitelist;

class Admin{


	public function defaultAction(){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('back/default', 'back');
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
		
		$view = new View('back/list', 'back');
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

		$view = new View('back/form', 'back');
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
		$user = $userObj->findOne();
		if(!$user){
			header("Location:" . DOMAIN . '/admin/users');
			exit();
		}
		$siteObj = new Site();
		$view = new View('back/form', 'back');

		##########
		/*update process here */
		##########

		$siteObj->setCreator($_GET['id']);
		$sites = $siteObj->findAll();


		if($sites){
			$fields = [ 'img', 'version', 'name', 'creation date', 'visit','edit' ];
			$datas = [];
			foreach($sites as $item){
				$visitBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$editBtn = '<a href="site?id=' . $item['id'] . '">Edit</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$item['creationDate'] = (new \DateTime($item['creationDate']))->format('d/m/y H:i:s');
				$formalized = "'" . $img . "','" . 0.0 . "','" . $item['name'] . "','" . $item['creationDate'] . "','" . $visitBtn . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
			$view->assign("fields", $fields);
			$view->assign("datas", $datas);
			$view->assign("list", true);
		}

		$form = $userObj->formEdit($user);
		$view->assign('pageTitle', "Manage a site");
		$view->assign("form", $form);

	}

	public function displayUsersAction(){
        $userObj = new User();
		$users = $userObj->findAll();
		$fields = [ 'avatar', 'id', 'name', 'mail', 'join date', 'role', 'see' ];
		$datas = [];

		if($users){
			foreach($users as $item){
				$name = $item['firstname'] . ' ' . $item['firstname'];
				$img = '<img src=' . DOMAIN . '/' . $item['avatar'] . ' width=100 height=80/>';
				$editBtn = '<a href="user?id=' . $item['id'] . '">Go</a>';

				$formalized = "'" . $img . "','" . $item['id'] . "','" . $name . "','" .$item['email'] .  "','" . $item['joinDate'] . "','" . $item['role'] . "','". $editBtn . "'";
				$datas[] = $formalized;
			}
		}
		//$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('back/list', 'back');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the users");
	}

	public function displayRolesAction(){
		$roleObj = new Role();
		$roles = $roleObj->findAll();
		$fields = [ 'name', 'description', 'icon', 'edit'];
		$datas = [];

		if($roles){
			foreach($roles as $item){
				$icon = '<img src=' . DOMAIN . '/' . $item['icon'] . ' width=100 height=80/>';
				$editBtn = '<a href="role?id=' . $item['id'] . '">Go</a>';
				$description = strlen($item['description']) != 0 ? $item['description'] : 'No description yet';
				$formalized = "'" . $item['name'] . "','" . $description . "','" . $icon . "','" . $editBtn ."'";
				$datas[] = $formalized;
			}
		}

		$view = new View('back/list', 'back');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the roles");
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

		$form = $roleObj->formEdit($role);

		$view = new View('back/form', 'back');
		$view->assign('pageTitle', "Edit a role");
		$view->assign("form", $form);
		if(!empty($_POST)){
			$errors = [];
			$errors = FormValidator::check($form, $_POST);
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			if(isset($_POST['description']) && strlen($_POST['description']) == 0){
				$_POST['description'] = 'IS NULL';
			}

			$saving = $roleObj->populate($_POST, TRUE);
			if(!$saving){
				$message = "Could not update this role!";
				$view->assign("message", $message);
				return;
			}else{
				\App\Core\Helpers::customRedirect('/admin/roles?success');
			}
		}

	}

}