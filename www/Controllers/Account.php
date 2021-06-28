<?php

namespace App\Controller;

use App\Core\Security as Secu;
use App\Core\View;

use App\Models\User;
use App\Models\Site;
use App\Models\Whitelist;

class Account{


	public function defaultAction(){
		if( Secu::isConnected() == 0 )
			return false;
        $userObj = new User();
		$userObj->setId(Secu::getUser());
		$user = $userObj->findOne();

		$view = new View('front/form', 'front');

		$form = $userObj->formEdit($user);
		$view->assign('pageTitle', "Account");
		$view->assign("form", $form);
	}

	public function mysitesAction(){
		if( Secu::isConnected() == 0 )
			return false;
		$wlistObj = new Whitelist();
		$wlistObj->setIdUser(Secu::getUser());
		if($wlistObj->getIdUser() == 0 )
			header("Location:" . DOMAIN . '/login');
		$wlists = $wlistObj->findAll();
		$siteObj = new Site();
		$sites = [];
		foreach($wlists as $wlist){
			$siteObj->setId($wlist["idSite"]);
			$site = $siteObj->findOne();
			array_push($sites, $site);
		}

		$fields = [ 'id', 'version', 'name', 'creator', 'subDomain', 'creation date', 'prefix', 'type', 'visit','edit' ];
		$datas = [];
        $userObj = new User();
		
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

		$view = new View('front/list', 'front');
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the sites");
	}


}