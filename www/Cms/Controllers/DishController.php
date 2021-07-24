<?php

namespace CMS\Controller;
use App\Core\FileUploader;
use App\Core\FormBuilder;
use App\Core\FormValidator;

use CMS\Models\Dish;
use CMS\Models\Dish_Category;

use CMS\Core\CMSView as View;
use CMS\Core\StyleBuilder;
use App\Core\Helpers as Helpers;

class DishController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function manageDishesAction($site){
		$dishObj = new Dish($site->getPrefix());
		$dishes = $dishObj->findAll();
		$dishesList = [];
		$content = "";
		$fields = [ 'id', 'image', 'name', 'category', 'price', 'Edit', 'Delete' ];
		$datas = [];
		$dishCatObj = new Dish_Category($site->getPrefix());

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

				$buttonEdit = '<a href="dish/edit?id=' . $item['id'] . '">Go</a>';
				$buttonDelete= '<a href="dish/delete?id=' . $item['id'] . '">Go</a>';
				$img = '<img src=' . DOMAIN . '/' . $item['image'] . ' width=100 height=80/>';
				$formalized = "'" . $item['id'] . "','" . $img . "','" . $item['name'] . "','" . $item['category'] .  "','" . $item['price'] . "','" . $buttonEdit . "','" . $buttonDelete . "'";
				$datas[] = $formalized;
			}
		}

		$addDishButton = ['label' => 'Add a new dish', 'link' => 'dish/create'];
		
		$view = new View('list', 'back', $site);
		$view->assign("createButton", $addDishButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the dishes");
	}

	public function createDishAction($site){
		$dishObj 	= new Dish($site->getPrefix());
		$dishCatObj = new Dish_Category($site->getPrefix());
		$dishCategories = $dishCatObj->findAll();
		$dishCatArr 	= [];
		
		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}

		$view = new View('createDish', 'back', $site);
		$view->assign("categories", $dishCatArr);
		$view->assign('submitLabel', "Add");
		$view->assign('pageTitle', "Add a dish");

		if(!empty($_POST) ) {
			try{
				$errors = [];

				[ "name" => $name, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
				[ "image" => $image ] = $_FILES;

				$form = $dishObj->formAdd();
				$data = array_merge($_POST, $_FILES);
				$errors = FormValidator::check($form, $data);
				if(count($errors) != 0){ 
					$errors[] = 'Form not accepted';
					throw new \Exception('Form not accepted'); 
				}

				$imgDir 	= "/uploads/cms/" . $site->getSubDomain() . "/dishes/";
				$imgTmpName = preg_replace("/[^A-Za-z0-9\s]+/", "", $name);
				$imgTmpName = preg_replace("/\s+/", "_", $imgTmpName);
				$imgName 	= $site->getSubDomain() . '_' . $imgTmpName;
				$image = FileUploader::uploadImage($image, $imgName, $imgDir);
				if( !$image ){ 
					$errors[] = 'Invalid or missing image';
					throw new \Exception('Invalid or missing image'); 
				}

				$data["image"] = $image;
				$adding = $dishObj->populate($data, TRUE);

				if($adding){
					$message ='Dish successfully added!';
					$view->assign("alert", Helpers::displayAlert("success",$message,3500));
				}else{
					$errors[] = "Cannot insert this dish";
					$view->assign("errors", $errors);
				}

			}catch(\Exception $e){
				$view->assign("errors", $errors);
			}
		}

	}

	public function editDishAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'dish not set ';
			header("Location: dishes");
			exit();
		}

		$dishObj = new Dish($site->getPrefix());
		$dishObj->setId($_GET['id']??0);
		$dish = $dishObj->findOne(TRUE);

		if(!$dish){
			header("Location: dishes");
			exit();
		}

		$dishCatObj = new Dish_Category($site->getPrefix());
		$dishCategories = $dishCatObj->findAll();
		$dishCatArr = [];
		
		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}

		$view = new View('createDish', 'back', $site);

		if(!empty($_POST) ) 
		{
			try{
				$errors = [];

				[ "name" => $name ]   = $_POST;
				[ "image" => $image ] = $_FILES;

				$form = $dishObj->formEdit();
				$data = array_merge($_POST, $_FILES);
				$errors = FormValidator::check($form, $data);
				if(count($errors) != 0){ 
					$errors[] = 'Form not accepted';
					throw new \Exception('Form not accepted'); 
				}

				//image is optionnal in editing
				if(!empty($image['name']) && strlen($image['name']) > 0)
				{
					$imgDir 	= "/uploads/cms/" . $site->getSubDomain() . "/dishes/";
					$imgTmpName = preg_replace("/[^A-Za-z0-9\s]+/", "", $name);
					$imgTmpName = preg_replace("/\s+/", "_", $imgTmpName);
					$imgName 	= $site->getSubDomain() . '_' . $imgTmpName;
					$image = FileUploader::uploadImage($image, $imgName, $imgDir);
					if( !$image ){ 
						$errors[] = 'Invalid or missing image';
						throw new \Exception('Invalid or missing image'); 
					}
					$data["image"] = $image;
				}

				$adding = $dishObj->populate($data, TRUE);

				if($adding){
					$message ='Dish successfully added!';
					$view->assign("alert", Helpers::displayAlert("success", $message, 3500));
				}else{
					$errors[] = "Cannot insert this dish";
					$view->assign("errors", $errors);
				}

			}catch(\Exception $e){
				$view->assign("errors", $errors);
			}
		}

		$view->assign("categories", $dishCatArr);
		$view->assign("name", preg_replace("/\\\+/", "", $dishObj->getName()));
		$view->assign("image", (DOMAIN . '/' . $dishObj->getImage()));
		$view->assign("notes", $dishObj->getNotes());
		$view->assign("allergens", $dishObj->getAllergens());
		$view->assign("description", $dishObj->getDescription());
		$view->assign("price", $dishObj->getPrice());
		$view->assign("category", $dishObj->getCategory());
		$view->assign('submitLabel', "Edit");
		$view->assign('pageTitle', "Update a dish");

	}

	public function deleteDishAction($site){
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('Dish not set'); }
			$dishObj = new Dish($site->getPrefix());
			$dishObj->setId($_GET['id']??0);
			$dish = $dishObj->findOne();
			if(!$dish){ throw new \Exception('No content found'); }
			$check = $dishObj->delete();
			if(!$check){ throw new \Exception('Cannot delete this dish'); }
			\App\Core\Helpers::customRedirect('/admin/dishes?success', $site);
		}catch(\Exception $e){
			echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/dishes?error', $site);
		}

	}

	public function getDishAction($site){
		$category = $_GET['category']??'';
		$dishObj = new Dish($site->getPrefix());
		if(isset($category)){
			$dishObj->setCategory($category);
		}

		$dishes = $dishObj->findAll();
		$dishArr = [];
		if(!$dishes){ 
			$code = 404;
		}else{
			$code = 200;
			foreach($dishes as $dish){
				$dishArr[] = array(
					'id' => $dish['id'],
					'name' => preg_replace("/\\\+/", "", $dish['name']),
					'image' => DOMAIN . '/' . $dish['image'],
					'price' => $dish['price']
				);
			}
		}

		http_response_code($code);
        echo json_encode(array('code' => $code, 'dishes' => ($dishArr)));
	}

	/*
	* Front vizualization
	*/

	public function renderDishAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			return 'dish id not set ';
		}
		$view = new View('dish', 'front', $site);

		$dishObj = new Dish($site->getPrefix());
		$dishObj->setId($_GET['id']);
		$dish = $dishObj->findOne();
		
        if(!$dish){
			$view->assign('notFound', true);
			$view->assign('pageTitle', 'Not found');
            return 'No content found :/';
        }

		$view->assign('pageTitle', 'Dishes available');
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('dish', $dish);
	}

}