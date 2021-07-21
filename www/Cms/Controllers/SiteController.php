<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Core\FileUploader;
use App\Core\Security;

use CMS\Core\CMSView as View;
use CMS\Core\StyleBuilder;

class SiteController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function editSiteAction($site){
		$siteObj = $site;
		$view = new View('create', 'back', $site);

		if(!empty($_POST) ) {
			[ "name" => $name, "description" => $description, "type" => $type] = $_POST;
			[ "image" => $image ] = $_FILES;

			if($name || $description || $type ){
				if(isset($image)){
					$imgDir = "/uploads/cms/" . $site->getSubDomain() . '/';
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

		$form = $siteObj->formEdit();
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit the site informations");

		

	}

	public function deleteSiteAction($site){
		$siteObj = new Site();
		$siteObj->setPrefix($site->getPrefix());
		$site = $siteObj->findOne();
		if(!$site){
			return;
		}
		if( $site->getCreator() != Security::getUser()){
			return;
		}
		$siteObj->deleteTables();
		\App\Core\Helpers::customRedirect('/');
	}

	/*
	* Front vizualization
	*/
	public function render($siteObj, $filter = null){
		$site = $siteObj->findOne();

		if(!empty($siteObj->getCreator()))
        {
			$userObj = new User();
			$userObj->setId($siteObj->getCreator());
        	$creator = $userObj->findOne();
			if($creator){
				$site['creator'] = $creator['firstname'] . " " . $creator['lastname'];
			}else{
				$site['creator'] = 'Unknown';
			}
		}

		$view = new View('about', 'front', $siteObj);
		$view->assign('pageTitle', 'About our restaurant');
		$view->assign("style", StyleBuilder::renderStyle($siteObj->returnData()));
		$view->assign('site', $site);

	}

}