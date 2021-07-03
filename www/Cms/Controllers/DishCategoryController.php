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

use CMS\Core\CMSView as View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

class DishCategoryController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function manageDishCategoriesAction($site){
		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCategories = $dishCatObj->findAll();
		$dishCatList = [];
		$content = "";
		$fields = [ 'id', 'name', 'description', 'notes', 'edit', 'delete'];
		$datas = [];

		if($dishCategories){
			foreach($dishCategories as $item){
				//$dishCatList[] = $dishCatObj->listFormalize($item);
				$buttonEdit = '<a href="editdishcategory?id=' . $item['id'] . '">Go</a>';
				$buttonDelete = '<a href="deletedishcategory?id=' . $item['id'] . '">Go</a>';
				$datas[] = "'".$item['id']."','".$item['name']."','".$item['description']."','".$item['notes']. "','" . $buttonEdit."','". $buttonDelete . "'";

			}
		}else{
			$content = "No dish category yet";
		}

		$addCatButton = ['label' => 'Add a new dish category', 'link' => 'createdishcategory'];
		
		$view = new View('back/list', 'back', $site);
		$view->assign("createButton", $addCatButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dish categories");
	}

	public function createDishCategoryAction($site){
		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCatArr = [];
		$dishCatArr[0] = 'None';

		$form = $dishCatObj->formAdd($dishCatArr);

		$view = new View('admin.create', 'back', $site);
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
			exit();
		}

		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCatObj->setId($_GET['id']??0);
		$dish = $dishCatObj->findOne();
		if(!$dish){
			header("Location: managedishcategories");
			exit();
		}

		$dishCatArr = [];
		$dishCatArr[0] = 'None';
		
		$dishArr = (array)$dish;
		$form = $dishCatObj->formEdit($dishArr);

		$view = new View('admin.create', 'back', $site);
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

	public function deleteDishCategoryAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'dish not set ';
			header("Location: managedishcategories");
			exit();
		}

		$dishCatObj = new DishCategory();
		$dishCatObj->setPrefix($site['prefix']);
		$dishCatObj->setId($_GET['id']??0);
		$dish = $dishCatObj->findOne();
		if(!$dish){
			header("Location: managedishcategories");
			exit();
		}
		$dishCatObj->delete();
		header("Location: managedishcategories");
		exit();
	}

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/

	public function renderList($site, $filter = null){
		$dishCatObj = new DishCategory();
        $dishCatObj->setPrefix($site->getPrefix());
		$dishCatList = $dishCatObj->findAll();

		$dishObj = new Dish();
		$dishObj->setPrefix($site->getPrefix());
        $categories = [];

        if($dishCatList && count($dishCatList) > 0 ){
			foreach($dishCatList as $category){
				$categoryDishes = [];
				$dishObj->setCategory($category['id']);
				$dishes = $dishObj->findAll();
				if($dishes){
					$categoryDishes[] = $dishes;
					$tmpCategory = ["category" => $category, "dishes" => $dishes] ;
					$categories[] = $tmpCategory;
				}
			}
		}
		$dishObj->setCategory('IS NULL');
		$dishes = $dishObj->findAll();

		if($dishes){
			$tmpCategory = ["category" => ["name" => "unclassified"], "dishes" => $dishes] ;
			$categories[] = $tmpCategory;
		}

		$view = new View('front/dishes', 'front', $site);
		$view->assign('pageTitle', 'Dish page');
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('categories', $categories);

	}

		


}