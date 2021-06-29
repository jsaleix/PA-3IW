<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Core\FileUploader;

use CMS\Models\Post;
use CMS\Models\Page;
use CMS\Models\Category;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

class SiteController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function editSiteAction($site){
		$siteObj = new Site();
        $siteObj->setId($site['id']);
		$view = new View('admin.create', 'back');

		if(!empty($_POST) ) {
			[ "name" => $name, "description" => $description, "type" => $type] = $_POST;
			[ "image" => $image ] = $_FILES;

			if($name || $description || $type ){
				if(isset($image)){
					$imgDir = "/uploads/cms/" . $site['subDomain'] . '/';
					$imgName = 'banner';
					$isUploaded = FileUploader::uploadImage($image, $imgName, $imgDir);
					if($isUploaded != false){
						$image = $isUploaded;
					}else{
						$image = null;
					}
				}else{
					$image = null;
				}

				$siteObj->setName($name);
				$siteObj->setDescription($description);
				$siteObj->setImage($image);
				$siteObj->setType($type);
				$adding = $siteObj->save();
				if($adding){
					$message ='Site successfully updated!';
					$view->assign("message", $message);
				}else{
					$errors = ["Error when updating the site"];
					$view->assign("errors", $errors);
				}
			}
		}

		$form = $siteObj->formEdit($site);
		$view->assign("navbar", navbarBuilder::renderNavbar((array)$site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit the site informations");

		

	}

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/
	public function render($siteObj, $filter = null){
		$site = $siteObj->returnData();


		if(!empty($site['creator']))
        {
			$userObj = new User();
			$userObj->setId($site['creator']);
        	$creator = $userObj->findOne();
			if($creator){
				$site['creator'] = $creator['firstname'] . " " . $creator['lastname'];
			}else{
				$site['creator'] = 'Unknown';
			}
		}
        
		$view = new View('front/about', 'front');
		$view->assign('pageTitle', 'About our restaurant');
		$view->assign("navbar", NavbarBuilder::renderNavbar($siteObj->returnData(), 'front'));
		$view->assign("style", StyleBuilder::renderStyle($siteObj->returnData()));
		$view->assign('site', $site);

	}

}