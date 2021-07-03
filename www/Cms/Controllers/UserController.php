<?php

namespace CMS\Controller;

use App\Models\Site;
use App\Models\User;
use App\Models\Whitelist;

use CMS\Core\CMSView as View;
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
		
		$view = new View('back/list', 'back', $site);
		$view->assign("createButton", $addDishButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dishes");
    }

    public function addAdminAction($site){
        $wlistObj = new Whitelist();
		$wlistObj->setIdSite($site['id']);

		$form = $dishCatObj->formAdd($dishCatArr);

		$view = new View('admin.create', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a dish category");

		if(!empty($_POST) )
		{
			$errors = [];
			[ "name" => $name, "description" => $description, "notes" => $notes ] = $_POST;
			
			if( $name ){
				//Verify the dishCategor submitted
				$dishCatObj->setName($name);
				$dishCatObj->setDescription($description);
				$dishCatObj->setNotes($notes);
				$dishCatObj->setIsActive($isActive??1);

				$adding = $dishCatObj->save();
				if($adding){
					$message ='Dish category successfully added!';
					$view->assign("message", $message);
				}else{
					$errors[] = "Cannot insert this dish category";
					$view->assign("errors", $errors);
				}
			}
		}
    }

    public function deleteAdminAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'user not set ';
		}
        $userObj = new User();
        $userObj->setId($_GET['id']);
        $user = $userObj->findOne();
        if(!$user){
            header("Location: ../users");
			exit();
        }
        $wlistObj = new Whitelist();
        $wlistObj->setIdSite($site['id']);
        $wlistObj->setIdUser($_GET['id']);
        $wlistObj->delete();
        header("Location: ../users");
		exit();
    }

}