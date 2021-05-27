<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Dish;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;

class DishController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function manageDishesAction($site){
		$dishObj = new Dish();
		$dishObj->setPrefix($site['prefix']);
		$dishes = $dishObj->findAll();
		$dishesList = [];
		$content = "";

		if($dishes){
			foreach($dishes as $item){
				$dishesList[] = $dishObj->listFormalize($item);
			}
		}else{
			$content = "No dish yet";
		}

		$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('admin.list', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("button", $addDishButton);
		$view->assign("list", $dishesList);
		$view->assign("content", $content);
		$view->assign('pageTitle', "Manage the dishes");
	}

	public function createDishAction($site){
		$dishObj = new Dish();
		$dishObj->setPrefix($site['prefix']);
		$dishCatArr = [];
		$dishCatArr[0] = 'None';
		/*$dishCatObj = new DishCategory();
		$dishCategories = $dishCatObj->findAll();
		if(!empty($actions)){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}*/

		//$form = $dishObj->formAdd($dishCatArr);
		$form = $dishObj->formAdd($dishCatArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a dish");

		if(!empty($_POST) ) {
			$errors = [];
			[ "name" => $name, "image" => $image, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
			
			if( $name ){
				//Verify the dishCategor submitted
				$dishObj->setName($name);
				$dishObj->setImage($image);
				$dishObj->setDescription($description);
				$dishObj->setPrice($price);
				$dishObj->setCategory($dishCat);
				$dishObj->setNotes($notes);
				$dishObj->setAllergens($allergens);
				$dishObj->setIsActive($isActive??1);

				$adding = $dishObj->save();
				if($adding){
					$message ='Dish successfully added!';
					$view->assign("message", $message);
				}else{
					$errors[] = "Cannot insert this dish";
					$view->assign("errors", $errors);
				}
			}
		}
	}

	public function editDishAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'dish not set ';
			header("Location: managepages");
		}

		$dishObj = new Dish();
		$dishObj->setPrefix($site['prefix']);
		$dishObj->setId($_GET['id']??0);
		$dish = $dishObj->findOne();
		if(!$dish){
			header("Location: managedishes");
		}

		$dishCatArr = [];
		$dishCatArr[0] = 'None';
		
		$dishArr = (array)$dish;
		$form = $dishObj->formEdit($dishArr, $dishCatArr);

		$view = new View('admin.create', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a dish");

		if(!empty($_POST) ) {
			$errors = [];
			[ "name" => $name, "image" => $image, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
			
			if( $name ){
				//Verify the dishCategor submitted
				$dishObj->setName($name);
				$dishObj->setImage($image);
				$dishObj->setDescription($description);
				$dishObj->setPrice($price);
				$dishObj->setCategory($dishCat);
				$dishObj->setNotes($notes);
				$dishObj->setAllergens($allergens);
				$dishObj->setIsActive($isActive??1);

				$adding = $dishObj->save();
				if($adding){
					$message ='Dish successfully updated!';
					$view->assign("message", $message);
				}else{
					$errors[] = "Cannot update this dish";
					$view->assign("errors", $errors);
				}
			}
		}

	}

}