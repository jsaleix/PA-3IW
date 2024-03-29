<?php

namespace CMS\Controller;

use App\Models\User;
use App\Models\Whitelist;
use App\Core\Security;
use App\Core\FormValidator;
use App\Models\Mail;

use CMS\Core\CMSView as View;

require_once __DIR__ . '/../../vendor/autoload.php';

class UserController{

    public function listAdminAction($site){//List all admins on a given site
        $wlistObj = new Whitelist();
        $wlistObj->setIdSite($site->getId());
        $wlist = $wlistObj->findAll();//go take all association from the site to the admins of it
        $userObj = new User();
        $fields = [ 'id', 'name', 'email', 'joinDate', 'Delete' ];
        $datas = [];
		if($wlist){//it there is admins
			foreach($wlist as $item){//loop on it to sanitize the display of the admins
                $userObj->setId($item['idUser']);
                $user = $userObj->findOne();
                if($user){
                    $name = $user['firstname'] . " " . $user['lastname']; 
					$username = '<a href="' . DOMAIN . '/profile?id='. $user['id'] .'">'.$name.'</a>';

                    $button = '<a href="users/delete?id=' . $user['id'] . '">Go</a>';
                    $formalized = "'" . $user['id'] . "','" . $username . "','" . $user['email'] . "','" . $user['joinDate'] .  "','" . $button . "'";
                    $datas[] = $formalized;
                }
			}
		}

		$addDishButton = ['label' => 'Add a new admin', 'link' => 'users/add'];
		
		$view = new View('list', 'back', $site);
		$view->assign("createButton", $addDishButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Users allowed to manage this site");
    }

    public function addAdminAction($site){//Function to add an admin to the site
        $wlistObj = new Whitelist();
		$wlistObj->setIdSite($site->getId());

		$form = $wlistObj->formAdd();

		$view = new View('whitelist', 'back', $site);

		try{
			if(!empty($_POST) )//on form submit
			{
				$errors = [];
				[ "user" => $user] = $_POST;
				if(empty($user)){ throw new \Exception('No user'); }
				if($user == Security::getUser()){ throw new \Exception('Cannot add yourself'); }
				if($site->getCreator() != Security::getUser()){ throw new \Exception('Cannot do this action'); }

				unset($_POST['username-visible']);//UNSET THE FIELD ONLY USED BY JS/FRONT
				$errors = FormValidator::check($form, $_POST);
                if( count($errors) > 0){
                    $view->assign("errors", $errors);
                    return;
                }
				$wlistObj->setIdUser($user);//Initialize a new object to check association
				$check = $wlistObj->findOne();
				if($check){  throw new \Exception('User already authorized');  }//If the user is already admin, return
				$adding = $wlistObj->save();
				if($adding){//check the addition on db
					$this->sendAdminMail($user, $site);
					\App\Core\Helpers::customRedirect('/admin/users?success', $site);
				}else{
					\App\Core\Helpers::customRedirect('/admin/users?error', $site);
				}
			}
		}catch(\Exception $e){
			\App\Core\Helpers::customRedirect('/admin/users?error', $site);
		}finally{
			$view->assign("form", $form);
			$view->assign('pageTitle', "Allow a user to manage your site");
		}
    }

    public function deleteAdminAction($site){//Delete an admin from the site
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('whitelist id not set');}
			if($site->getCreator() != Security::getUser()){ throw new \Exception('Cannot do this action'); }//check that only the creator can delete admùins
			
			$userObj = new User();//Try to find the user in db
			$userObj->setId($_GET['id']);
			$user = $userObj->findOne();
			if(!$user){ throw new \Exception('Cannot find this user on the whitelist'); }
			
			$wlistObj = new Whitelist();//Find the association of the user
			$wlistObj->setIdSite($site->getId());
			$wlistObj->setIdUser($_GET['id']);
			$check = $wlistObj->delete();
			
			if(!$check){ throw new \Exception('Cannot delete this user from whitelist');}//Check the deletion
			\App\Core\Helpers::customRedirect('/admin/users?success', $site);
		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/users?error', $site);
		}
    }

	public function sendAdminMail($user, $site){
		/*Sending mail to inform the new administrator*/
		$receiver = new User();
		$receiver->setId($user);
		$receiver->findOne(TRUE);

		$sender = new User();
		$sender->setId(Security::getUser());
		$sender->findOne(TRUE);
		$body = "<h3>EasyMeal</h3><br>";
		$body .= "<h2>".$sender->getFullName()." has allowed you to manage his site <a href='" . \App\Core\Helpers::renderCMSLink('', $site) . "'>" . $site->getName() ."/" . $site->getSubDomain() . "</a></h2>";
		$body .= "<hr>";
		$body .= "<p>To access the admin panel add in the url after the site domain /admin or click <a href='" . \App\Core\Helpers::renderCMSLink('admin', $site) . "'>here</a></p>";
		$mail = array( 'from' => 'EasyMeal', 'to' => $receiver->getEmail(), 'subject' => $sender->getFullName() .' allowed you on '.$site->getName() , 'body' => $body);
		$mailer = new Mail();
		$mailer->sendMail($mail);
	}

}