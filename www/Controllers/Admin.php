<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;
use App\Core\FormValidator;
use App\Core\ConstantMaker as c;
use App\Core\Token;

use App\Models\User;
use App\Models\MailToken;
use App\Models\Site;

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
				
				$editBtn = '<a href="site?id=' . $item['id'] . '">Go</a>';
                $creatorBtn = '<a href="user?id=' .  $item['creator'] . '">' . $creator['firstname'] . ' ' . $creator['lastname']. '</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . 0.0 . "','" . $item['name'] . "','" .$creatorBtn .  "','" . $item['subDomain'] . "','" . $item['creationDate'] . "','". $item['prefix'] . "','". $item['type'] . "','" . $item['name'] . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
		}
		//$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('back/list', 'back');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the sites");
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

}