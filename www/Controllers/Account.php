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
		if( !Secu::isConnected() )
			header("Location:" . DOMAIN . '/login');
		
		$siteObj = new Site();
		$userObj = new User();
		$lists = [];

		//Fetching all the sites owned by the user
		$siteObj->setCreator(Secu::getUser());
		$ownedSites = $siteObj->findAll();
		if($ownedSites){
			$datas = [];
			$fields = [ 'id', 'version', 'name', 'subDomain', 'creation date', 'prefix', 'type', 'visit','edit' ];
			foreach($ownedSites as $item){
                $userObj->setId($item['creator']);
                $creator = $userObj->findOne();
				$visitBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$editBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '/admin/settings">Edit</a>';
                $creatorBtn = '<a href="user?id=' .  $item['creator'] . '">' . $creator['firstname'] . ' ' . $creator['lastname']. '</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . 0.0 . "','" . $item['name'] . "','" . $item['subDomain'] . "','" . $item['creationDate'] . "','". $item['prefix'] . "','". $item['type'] . "','" . $visitBtn . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
			$lists[] = array( "title" => "Sites you own", "datas" => $datas, "id" => "owned_sites", "fields" => $fields );
		}

		//Fetching all the sites on which the user is whitelisted on
		$wlistObj = new Whitelist();
		$wlistObj->setIdUser(Secu::getUser());
		$wlists = $wlistObj->findAll();
		$sites = [];
		if($wlists){
			foreach($wlists as $wlist){
				$siteObj->setId($wlist["idSite"]);
				$site = $siteObj->findOne();
				array_push($sites, $site);
			}
		}
		$fields = [ 'id', 'version', 'name', 'creator', 'subDomain', 'creation date', 'prefix', 'type', 'visit','edit' ];
		$datas = [];

		if($sites){
			foreach($sites as $item){
                $userObj->setId($item['creator']);
                $creator = $userObj->findOne();
				$visitBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '">Go</a>';
				$editBtn = '<a href="'. DOMAIN . '/site/' . $item['subDomain'] . '/admin/settings">Edit</a>';
                $creatorBtn = '<a href="user?id=' .  $item['creator'] . '">' . $creator['firstname'] . ' ' . $creator['lastname']. '</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . 0.0 . "','" . $item['name'] . "','" .$creatorBtn .  "','" . $item['subDomain'] . "','" . $item['creationDate'] . "','". $item['prefix'] . "','". $item['type'] . "','" . $visitBtn . "','" . $editBtn . "'";
				$datas[] = $formalized;
			}
		}
		$lists[] = array( "title" => "Sites you are whitelisted on", "datas" => $datas, "id" => "shared_sites", "fields" => $fields );
		//var_dump($lists[1]);
		$view = new View('front/list.account', 'front');
		$view->assign("lists", $lists);
		$view->assign('pageTitle', "Manage the sites");
	}


}