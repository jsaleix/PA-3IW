<?php

namespace CMS\Controller;

use App\Models\Site;
use App\Models\User;
use App\Models\Whitelist;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

class UserController{

    public function listAdminAction($site){
        $wlistObj = new Whitelist();
        $wlistObj->setIdSite($site['id']);
        $wlist = $wlistObj->findAll();
        $userObj = new User();
        $fields = [ 'id', 'name', 'email', 'joinDate', 'Delete' ];
        $datas = [];

		if($wlist){
			foreach($wlist as $item){
                $userObj->setId($item['idUser']);
                $user = $userObj->findOne();
                if($user){
                    $name = $user['firstname'] . " " . $user['lastname']; 
                    $button = '<a href="users/delete?id=' . $user['id'] . '">Go</a>';
                    $formalized = "'" . $user['id'] . "','" . $name . "','" . $user['email'] . "','" . $user['joinDate'] .  "','" . $button . "'";
                    $datas[] = $formalized;
                }
			}
		}

		$addDishButton = ['label' => 'Add a new admin', 'link' => 'users/add'];
		
		$view = new View('back/list', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("createButton", $addDishButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dishes");
    }

    public function addAdminAction($site){
        echo "add";
    }

    public function deleteAdminAction($site){
        echo "delete";
    }

}