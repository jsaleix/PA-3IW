<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\View;

use App\Models\User;

class Main{


	public function defaultAction(){
		$view = new View("home");
		if(Security::isConnected()){
			$userObj = new User();
			$userObj->setId(Security::getUser());
			$username = $userObj->findOne();
			$username = $username['firstname'] . ' ' . $username['lastname'];
			$view->assign("connected", $userObj->getId());
			$view->assign("pseudo", $userObj->getId() != 0 ? $username : '');
		}
	}


}