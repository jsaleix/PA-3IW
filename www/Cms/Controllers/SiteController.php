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
        $siteObj->setName($site['name']);
        $siteObj->setDescription($site['description']);
        $siteObj->setImage($site['image']);
        $siteObj->setCreator($site['creator']);
        $siteObj->setSubDomain($site['subDomain']);
        $siteObj->setPrefix($site['prefix']);
        $siteObj->setType($site['type']);

		$form = $siteObj->formEdit($site);
		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavbar((array)$site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit the site informations");

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

	}

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/
	public function render($siteObj, $filter = null){
		$siteData = $siteObj->returnData();
        $creatorData = new User();
        extract($siteData);

		$image = strpos($image, 'http') !== false ? $image : DOMAIN . '/' . $image ;
		if(!empty($creator))
        {
			$creatorData->setId($creator);
        	$creator = $creatorData->findOne();
			$creatorName = $creator['firstname'] . " " . $creator['lastname'];
		}else{
			$creatorName = 'Unknown';
		}
        


		$html = '<h2>' . $name . '\'s restaurant</h2>';
		$html .= "<image src=${image} alt='${name}image'/>";
		$html .= '<p>' . $description . '</p>';
		$html .= '<p>Type of food: ' . $type . '</p>';
		$html .= '*****';
		$html .= '<p id='. $creator['id'] .' >Created by ' . $creatorName . ' </p>';

		$view = new View('cms', 'front');
		$view->assign('pageTitle', 'Restaurant informations');
		$view->assign("navbar", NavbarBuilder::renderNavbar($siteObj->returnData(), 'front'));
		$view->assign("style", StyleBuilder::renderStyle($siteObj->returnData()));
		$view->assign('content', $html);

	}

}