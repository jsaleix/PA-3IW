<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;
use App\Core\FileUploader;
use App\Core\FormValidator;
use App\Core\Security;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Post_Medium_Association as PMAssoc;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;
use CMS\Models\Medium;

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;

class MediaController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
	}

	public function listMediasAction($site){
		$mediumObj = new Medium($site['prefix']);
		$media = $mediumObj->findAll();
		$mediumList = [];
		$content = "";
		$fields = ['id', 'image', 'name', 'publicationDate', 'Edit', 'Delete'];
		$datas = [];

		if($media){
			foreach($media as $item){
				$img = "<img src=".DOMAIN."/".$item['image']." width=100 height=80/>";
				$buttonEdit = "<a href=\"medium/edit?id=".$item['id']."\">Go</a>";
				$buttonDelete = "<a href=\"medium/delete?id=".$item['id']."\">Go</a>";
				$formalized = "'".$item['id']."','".$img."','".$item['name']."','".$item['publicationDate']."','".$buttonEdit."','".$buttonDelete."'";
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
		$mediumObj = new Medium($site['prefix']);
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
			$imgDir = "/uploads/cms/" . $site['subDomain'] . "/library/";
			$imgName = $date->format("Ymd_Hisu");
			$isUploaded = FileUploader::uploadImage($_FILES["image"], $imgName, $imgDir);
			$image = $isUploaded ? $isUploaded : null;
			$data["image"] = $image;
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
		$mediumObj = new Medium($site['prefix']);
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$postObj = new Post($site['prefix']);
		$posts = $postObj->findAll();
		/*$postAssoc = new PMAssoc($site['prefix']);
		$postAssoc->set
		print_r($post);*/
		//$mediumObj->formEdit($medium, $posts, $post);

	}

	public function deleteMediumAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) )
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		$mediumObj = new Medium($site['prefix']);
		$mediumObj->setId($_GET['id']??0);
		$medium = $mediumObj->findOne();
		if(!$medium)
			\App\Core\Helpers::customRedirect('/admin/medium', $site);
		echo "Would have delete Medium with id ".$mediumObj->getId()." but working on it tho it's disabled";
		$mediumObj->delete();
		\App\Core\Helpers::customRedirect('/admin/medium', $site);
	}

}