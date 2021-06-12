<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;
use App\Core\FileUploader;

use CMS\Models\Content;
use CMS\Models\Page;
use CMS\Models\Category;
use CMS\Models\Dish;
use CMS\Models\DishCategory;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

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
		$fields = [ 'id', 'image', 'name', 'category', 'price', 'Edit' ];
		$datas = [];
		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);

		if($dishes){
			foreach($dishes as $item){
				if($item['category']){
					$dishCatObj->setId($item['category']);
					$dishCat = $dishCatObj->findOne();
					if($dishCat){
						$item['category'] = $dishCat['name'];
					}
				}else{
					$item['category'] = 'No category';
				}

				$button = '<a href="editDish?id=' . $item['id'] . '">Go</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . $img . "','" . $item['name'] . "','" . $item['category'] .  "','" . $item['price'] . "','" . $button . "'";
				$datas[] = $formalized;
			}
		}

		$addDishButton = ['label' => 'Add a new dish', 'link' => 'createdish'];
		
		$view = new View('back/list', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		$view->assign("createButton", $addDishButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dishes");
	}

	public function createDishAction($site){
		$dishObj = new Dish();
		$dishObj->setPrefix($site['prefix']);

		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCategories = $dishCatObj->findAll();
		$dishCatArr = [];
		
		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}

		$view = new View('/back/createDish', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("categories", $dishCatArr);
		$view->assign('pageTitle', "Add a dish");

		if(!empty($_POST) ) {
			$errors = [];
			[ "name" => $name, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
			[ "image" => $image ] = $_FILES;
			if( $name ){
				//Verify the dishCategor submitted
				if(isset($image) && !empty($image) && $image['size'] != 0){
					$imgDir = "/uploads/cms/" . $site['subDomain'] . "/dishes/";
					$imgName = $site['subDomain'].'_'. trim($name);
					$isUploaded = FileUploader::uploadImage($image, $imgName, $imgDir);
					if($isUploaded != false){
						$image = $isUploaded;
					}else{
						$image = null;
					}
				}else{
					$image = null;
				}

				$dishCat = ($dishCat == 0) ? null : $dishCat ; 		
				$dishObj->setName($name);
				$dishObj->setImage($image);
				$dishObj->setDescription($description);
				$dishObj->setPrice($price);
				$dishObj->setCategory($dishCat);
				$dishObj->setNotes($notes);
				$dishObj->setAllergens($allergens);
				$dishObj->setIsActive($isActive??1);

				$adding = $dishObj->save();
				$adding = true;
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
			header("Location: managedishes");
		}

		$dishObj = new Dish();
		$dishObj->setPrefix($site['prefix']);
		$dishObj->setId($_GET['id']??0);
		$dish = $dishObj->findOne();
		if(!$dish){
			header("Location: managedishes");
		}

		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCategories = $dishCatObj->findAll();
		$dishCatArr = [];
		
		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}

		/*$dishArr = (array)$dish;
		$form = $dishObj->formEdit($dishArr, $dishCatArr);
		$form = $dishObj->formAdd($dishCatArr);*/

		$view = new View('/back/createDish', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("categories", $dishCatArr);
		$view->assign("name", $dish['name']);
		$view->assign("image", (DOMAIN . '/' . $dish['image']));
		$view->assign("notes", $dish['notes']);
		$view->assign("allergens", $dish['name']);
		$view->assign("description", $dish['description']);
		$view->assign("price", $dish['price']);
		$view->assign("category", $dish['category']);
		$view->assign('pageTitle', "Add a dish");

		if(!empty($_POST) ) {
			$errors = [];
			[ "name" => $name, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
			[ "image" => $image ] = $_FILES;

			if( $name ){
				if(isset($image) && !empty($image) && $image['size'] > 0){
					$imgDir = "/uploads/cms/" . $site['subDomain'] . "/dishes/";
					$imgName = $site['subDomain'].'_'. $_GET['id'];
					$isUploaded = FileUploader::uploadImage($image, $imgName, $imgDir);
					if($isUploaded != false){
						$image = $isUploaded;
					}else{
						$image = null;
					}
				}else{
					$image = null;
				}
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

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/

	//$site is an instance of Site
	public function renderDishAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			return 'dish id not set ';
		}

		$dishObj = new Dish();
        $dishObj->setPrefix($site->getPrefix());
		$dishObj->setId($_GET['id']);
		$dishObj->setIsActive(1);
        $dish = $dishObj->findOne();
        if(!$dish){
            return 'No content found :/';
        }

		$view = new View('front/dish', 'front');
		$view->assign('pageTitle', 'Dishes available');
		$view->assign("navbar", NavbarBuilder::renderNavbar($site->returnData(), 'front'));
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('dish', $dish);
	}

}