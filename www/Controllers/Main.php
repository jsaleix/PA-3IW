<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\View;

use App\Models\User;

class Main{


	public function defaultAction(){
		
        $isConnected = Security::isConnected();
		if($isConnected){
			$userObj = new User();
			$userObj->setId(Security::getUser());
			$username = $userObj->findOne();
			$username = $username['firstname'] . ' ' . $username['lastname'];
		}
		$view = new View("home");
		$view->assign("connected", $isConnected);
        $view->assign("pseudo", $isConnected ? $username : '');


	}


}