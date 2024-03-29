<?php

namespace CMS\Controller;
use App\Core\FileUploader;
use App\Core\FormValidator;
use App\Core\Security;

use CMS\Models\Post;
use CMS\Models\Post_Medium_Association as PMAssoc;
use CMS\Models\Medium;

use CMS\Core\CMSView as View;

class MediaController{

	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function listMediasAction($site){
		$mediumObj = new Medium($site->getPrefix());
		$media = $mediumObj->findAll();
		$fields = ['id', 'image', 'name', 'type', 'publisher', 'publicationDate', 'Edit', 'Delete'];
		$datas = [];

		if($media){
			foreach($media as $item){
				$img = "<img src=".DOMAIN."/".$item['image']." width=100 height=80/>";
				$buttonEdit = "<a href=\"medium/edit?id=".$item['id']."\">Go</a>";
				$buttonDelete = "<a href=\"medium/delete?id=".$item['id']."\">Go</a>";
				$formalized = "'".$item['id']."','".$img."','".$item['name']."','". $item['type']."','". $item['publisher']."','".$item['publicationDate']."','".$buttonEdit."','".$buttonDelete."'";
				$datas[] = $formalized;
			}
		}
		$addMediumButton = ['label' => 'Add a new Medium', 'link' => 'medium/create'];

		$view = new View('list', 'back', $site);
		$view->assign("createButton", $addMediumButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dishes");
	}

	public function createMediumAction($site){
		$mediumObj = new Medium($site->getPrefix());
		$form = $mediumObj->formAdd();

		$view = new View('create', 'back', $site);
		$view->assign("form", $form);
		$view->assign("pageTitle", "Add a medium");

		if( !empty($_POST)){
			$errors = [];
			$data = array_merge($_POST, $_FILES);
			$errors = FormValidator::check($form, $data);
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			$date = new \DateTime();
			$imgDir = "/uploads/cms/" . $site->getSubDomain() . "/library/";
			$imgName = $date->format("Ymd_Hisu");
			$isUploaded = FileUploader::uploadImage($_FILES["image"], $imgName, $imgDir);
			$image = $isUploaded ? $isUploaded : null;
			$data["image"] = $image;
			$data["publisher"] = Security::getUser();
			$pdoResult = $mediumObj->populate($data, TRUE);
			if( $pdoResult ){
				$message = "Medium successfully added!";
				$view->assign("message", $message);
			} else {
				$errors[] = "Cannot insert this medium";
				$view->assign("errors", $errors);
			}
		}
	}

	public function editMediumAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) )
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj = new Medium($site->getPrefix());
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj->populate($medium);
		$formEdit = $mediumObj->formEdit($medium);
		$view = new View('create.list', 'back', $site);
		$view->assign("form", $formEdit);
		$view->assign("pageTitle", "Edit a medium");

		$PMAObj = new PMAssoc($site->getPrefix());
		$PMAObj->setMedium($mediumObj->getId());
		$PMAS = $PMAObj->findAll();
		$fields = ['id', 'medium', 'post', 'Delete'];
		$datas = [];
		
		if($PMAS){
			foreach($PMAS as $item){
				$postObj = new Post($site->getPrefix());
				$postObj->setId($item['post']);
				$postObj->findOne(TRUE);
				$buttonDelete = "<a href=".\App\Core\Helpers::renderCMSLink("admin/medium/assoc/delete?id=".$item['id'], $site).">Go</a>";
				//$buttonDelete = "<a href=\"medium/assoc/delete?id=".$item['id']."\">Go</a>";
				$formalized = "'".$item['id']."','".$mediumObj->getName()."','".$postObj->getTitle()."','".$buttonDelete."'";
				$datas[] = $formalized;
			}
		}
		
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);

		if( !empty($_POST)){
			$errors = [];
			$data = array_merge($_POST, $_FILES);
			print_r($_FILES);
			$errors = FormValidator::check($formEdit, $data);
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			if( !empty($data["image"]["name"])){
				$date = new \DateTime();
				$imgDir = "/uploads/cms/" . $site->getSubDomain() . "/library/";
				$imgName = $date->format("Ymd_Hisu");
				$isUploaded = FileUploader::uploadImage($_FILES["image"], $imgName, $imgDir);
				$image = $isUploaded ? $isUploaded : null;
				$data["image"] = $image;
			} else {
				unset($data["image"]);
			}
			$pdoResult = $mediumObj->edit($data);
			if( $pdoResult ){
				$message = "Medium successfully modified!";
				$view->assign("message", $message);
				\App\Core\Helpers::customRedirect('/admin/medium', $site);
			} else {
				$errors[] = "No modification made on this medium";
				$view->assign("errors", $errors);
			}
		}
	}

	public function deleteMediumAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) )
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj = new Medium($site->getPrefix());
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj->delete();
		\App\Core\Helpers::customRedirect('/admin/medium', $site);
	}

}