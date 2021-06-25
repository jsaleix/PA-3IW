<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;

use App\Models\User;

class Main{


	public function defaultAction(){
		
		$pseudo = "Super Prof"; //Plus tard on le récupèrera depuis la bdd
        $isConnected = Security::isConnected();
		$view = new View("home");
		$view->assign("connected", $isConnected);
        $view->assign("pseudo", $isConnected ? Security::getUser() : '');


	}


}