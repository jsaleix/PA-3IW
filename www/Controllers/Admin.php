<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;
use App\Core\Token;
use App\Core\FileUploader;

use App\Models\User;
use App\Models\Site;
use App\Models\Whitelist;

class Admin{


	public function defaultAction(){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('back/default', 'back');
		//$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
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
		}
		$siteObj = new Site();
		$siteObj->setId($_GET['id']);
		$site = $siteObj->findOne();
		if(!$site){
			header("Location:" . DOMAIN . '/admin/sites');
		}
		$form = $siteObj->formEdit($site);

		$view = new View('back/form', 'back');
		$view->assign('pageTitle', "Manage a site");
		$view->assign("form", $form);
	}

	public function displayUserAction(){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			header("Location:" . DOMAIN . '/admin/users');
		}
		$userObj = new User();
		$userObj->setId($_GET['id']);
		$user = $userObj->findOne();
		if(!$user){
			header("Location:" . DOMAIN . '/admin/users');
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


	public function displayMySitesAction(){
		$wlistObj = new Whitelist();
		$wlistObj->setIdUser(Token::verifyToken());
		if($wlistObj->getIdUser() == 0 )
			header("Location:" . DOMAIN . '/login');
		$wlists = $wlistObj->findAll();
		$siteObj = new Site();
		foreach($wlists as $wlist){
			$siteObj->setId($wlist["idSite"]);
			$site = $siteObj->findOne();
			print_r($site);
			echo "<br>";
		}
	}

}