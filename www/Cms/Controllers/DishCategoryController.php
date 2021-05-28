<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;

class DishCategoryController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function manageDishCategoriesAction($site){
		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCategories = $dishCatObj->findAll();
		$dishCatList = [];
		$content = "";

		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatList[] = $dishCatObj->listFormalize($item);
			}
		}else{
			$content = "No dish category yet";
		}

		$addCatButton = ['label' => 'Add a new dish category', 'link' => 'createdishcategory'];
		
		$view = new View('admin.list', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("button", $addCatButton);
		$view->assign("list", $dishCatList);
		$view->assign("content", $content);
		$view->assign('pageTitle', "Manage the dish categories");
	}

	public function createDishCategoryAction($site){
		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCatArr = [];
		$dishCatArr[0] = 'None';

		$form = $dishCatObj->formAdd($dishCatArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
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

	public function editDishCategoryAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'dish not set ';
			header("Location: managedishcategories");
		}

		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCatObj->setId($_GET['id']??0);
		$dish = $dishCatObj->findOne();
		if(!$dish){
			header("Location: managedishcategories");
		}

		$dishCatArr = [];
		$dishCatArr[0] = 'None';
		
		$dishArr = (array)$dish;
		$form = $dishCatObj->formEdit($dishArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a dish catergory");

		if(!empty($_POST) ) {
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
					$message ='Dish category successfully updated!';
					$view->assign("message", $message);
				}else{
					$errors[] = "Cannot update this dish category";
					$view->assign("errors", $errors);
				}
			}
		}

	}

}