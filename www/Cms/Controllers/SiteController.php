<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Core\FileUploader;
use App\Core\Security;
use App\Core\FormValidator;
use App\Core\ErrorReporter;

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
		$view = new View('settings', 'back', $site);
		$form = $siteObj->formEdit();

		if(!empty($_POST) ) {
			try{
				[ "name" => $name, "description" => $description, "type" => $type] = $_POST;
				[ "image" => $image ] = $_FILES;
				$data = array_merge($_POST, $_FILES);
				$errors = FormValidator::check($form, $data);
				if(count($errors) != 0){ 
					$errors[] = 'Form not accepted';
					throw new \Exception('Form not accepted'); 
				}

				if(isset($data['image'])){
					$imgDir = "/uploads/cms/" . $site->getSubDomain() . '/';
					$imgName = 'banner';
					$isUploaded = FileUploader::uploadImage($data['image'], $imgName, $imgDir);
					
					if($isUploaded != false){
						$data['image']= $isUploaded;
					}else{
						$data['image'] = null;
					}
				}

				$adding = $siteObj->edit($data);
				if($adding){
					\App\Core\Helpers::customRedirect('/admin/settings', $site);
				}else{
					throw new \Exception('Not added'); 
				}
			}catch(\Exception $e){
				$errors = ["Error when updating the site"];
				$view->assign("errors", $errors);
			}
		}

		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit the site informations");
		$view->assign('deletePage', false);
	}

	public function deleteSiteAction($site){
		$user = Security::getUser();
		if($user !== $site->getCreator()){
			\App\Core\Helpers::customRedirect('/admin?not_allowed', $site);
		}
		$view = new View('settings', 'back', $site);
		$form = $site->formDelete();
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit the site informations");
		$view->assign('deletePage', true);

		if(!empty($_POST) && isset($_POST['_method']) && $_POST['_method'] == 'delete')//since there is not $_DELETE in php
		{
			try{
				$errors = [];
				$errors = FormValidator::check($form, $_POST);
				if( count($errors) > 0){
					$view->assign("errors", $errors);
					return;
				}
				$deletion = $site->delete();
				if(!$deletion){
					$errors[] = 'Couldn\'t delete this site';
					$view->assign("errors", $errors);
				}
				\App\Core\Helpers::customRedirect('/account/sites');
			}catch(\Exception $e){
				ErrorReporter::report("SiteController deleteSite:" . $e->getMessage() );
				$errors[] = 'Couldn\'t delete this site';
				$view->assign("errors", $errors);
			}
		}
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