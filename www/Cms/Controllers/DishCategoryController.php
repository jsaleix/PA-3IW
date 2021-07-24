<?php

namespace CMS\Controller;
use App\Core\FormValidator;

use CMS\Models\Dish;
use CMS\Models\DishCategory;

use CMS\Core\CMSView as View;
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
		$dishCatObj = new DishCategory($site->getPrefix());
		$dishCategories = $dishCatObj->findAll();
		$dishCatList = [];
		$content = "";
		$fields = [ 'id', 'name', 'description', 'notes', 'edit', 'delete'];
		$datas = [];

		if($dishCategories){
			foreach($dishCategories as $item){
				//$dishCatList[] = $dishCatObj->listFormalize($item);
				$buttonEdit = '<a href="dishcategory/edit?id=' . $item['id'] . '">Go</a>';
				$buttonDelete = '<a href="dishcategory/delete?id=' . $item['id'] . '">Go</a>';
				$datas[] = "'".$item['id']."','".$item['name']."','".$item['description']."','".$item['notes']. "','" . $buttonEdit."','". $buttonDelete . "'";

			}
		}else{
			$content = "No dish category yet";
		}

		$addCatButton = ['label' => 'Add a new dish category', 'link' => 'dishcategory/create'];
		
		$view = new View('list', 'back', $site);
		$view->assign("createButton", $addCatButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dish categories");
	}

	public function createDishCategoryAction($site){
		$dishCatObj = new DishCategory($site->getPrefix());
		$dishCatArr = [];
		$dishCatArr[0] = 'None';

		$form = $dishCatObj->formAdd($dishCatArr);

		$view = new View('create', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a dish category");

		if(!empty($_POST) )
		{
			$errors = [];
			$errors = FormValidator::check($form, $_POST);
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			$pdoResult = $dishCatObj->populate($_POST, TRUE);
			if( $pdoResult ){
				$message = "Dish category successfully added!";
				$view->assign("message", $message);
			} else {
				$errors[] = "Cannot insert this dish category";
				$view->assign("errors", $errors);
			}
		}
	}

	public function editDishCategoryAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'dish not set ';
			header("Location: dishcategories");
			exit();
		}

		$dishCatObj = new DishCategory($site->getPrefix());
		$dishCatObj->setId($_GET['id']??0);
		$dish = $dishCatObj->findOne();
		if(!$dish){
			header("Location: dishcategories");
			exit();
		}

		$dishCatArr = [];
		$dishCatArr[0] = 'None';
		
		$dishArr = (array)$dish;
		$form = $dishCatObj->formEdit($dishArr);

		$view = new View('create', 'back', $site);
		$view->assign("form", $form);
		$view->assign('pageTitle', "Edit a dish catergory");

		if(!empty($_POST) ) {
			$errors = [];
			$errors = FormValidator::check($form, $_POST);
			if( count($errors) > 0){
				$view->assign("errors", $errors);
				return;
			}
			$pdoResult = $dishCatObj->populate($_POST, TRUE);
			if($pdoResult){
				$message ='Dish category successfully updated!';
				$view->assign("message", $message);
				\App\Core\Helpers::customRedirect('/admin/dishcategories?success', $site);
			}else{
				$errors[] = "Cannot update this dish category";
				$view->assign("errors", $errors);
			}
		}
	}

	public function deleteDishCategoryAction($site){
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('dish category not set');}
			$dishCatObj = new DishCategory($site->getPrefix());
			$dishCatObj->setId($_GET['id']??0);
			$dish = $dishCatObj->findOne();
			if(!$dish){ throw new \Exception('dish category not found');}
			$check = $dishCatObj->delete();
			if(!$check){ throw new \Exception('Cannot delete this article');}
			\App\Core\Helpers::customRedirect('/admin/dishcategories?success', $site);
		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/dishcategories?error', $site);
		}
	}

	/*
	* Front vizualization
	* returns html for pageRenderer
	*/

	public function renderList($site, $filter = null){
		$dishCatObj = new DishCategory($site->getPrefix());
		$dishCatList = $dishCatObj->findAll();

		$dishObj = new Dish($site->getPrefix());
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

		$view = new View('dishes', 'front', $site);
		$view->assign('pageTitle', 'Dish page');
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('categories', $categories);

	}

		


}