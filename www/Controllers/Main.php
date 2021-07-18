<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\View;

use App\Models\User;
use App\Models\Site;
use App\Models\Role;

class Main{


	public function defaultAction(){
		$view = new View("home");
		if(Security::isConnected()){
			$userObj = new User();
			$userObj->setId(Security::getUser());
			$username = $userObj->findOne();
			$username = $username['firstname'] . ' ' . $username['lastname'];
			$view->assign("connected", $userObj->getId());
			$view->assign("isAdmin", Security::isAdmin());
			$view->assign("pseudo", $userObj->getId() != 0 ? $username : '');
		}
	}

	public function displayUserProfileAction(){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			header("Location:" . DOMAIN . '/');
			exit();
		}
		$userObj = new User();
		$userObj->setId($_GET['id']);
		$user = $userObj->findOne(TRUE);
		if(!$user){
			header("Location:" . DOMAIN . '/');
			exit();
		}
		$role = new Role();
		$role->setId($userObj->getId());
		$role->findOne(TRUE);

		$view = new View('front/profile', 'front');

		$siteObj = new Site();
		$siteObj->setCreator($_GET['id']);
		$sites = $siteObj->findAll();

		if($sites){
			$fields = [ 'img', 'name', 'creation date', 'visit' ];
			$datas = [];
			foreach($sites as $item){
				$visitBtn = '<a target="-blank" href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$item['creationDate'] = (new \DateTime($item['creationDate']))->format('d/m/y H:i:s');
				$formalized = "'" . $img . "','" . $item['name'] . "','" . $item['creationDate'] . "','" . $visitBtn . "'";
				$datas[] = $formalized;
			}
			$view->assign("fields", $fields);
			$view->assign("datas", $datas);
			$view->assign("list", true);
		}

		$view->assign('pageTitle', $userObj->getFirstname() . ' ' . $userObj->getLastname() . '\'s profile');
		$view->assign("user", $userObj);
		$view->assign("role", $role);

	}


}