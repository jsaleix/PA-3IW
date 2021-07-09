<?php

namespace CMS\Controller;
use App\Core\FileUploader;

use CMS\Models\Dish;
use CMS\Models\DishCategory;

use CMS\Core\CMSView as View;
use CMS\Core\StyleBuilder;

class DishController{


	public function defaultAction($site){
		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('admin', 'back', $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		
	}

	public function manageDishesAction($site){
		$dishObj = new Dish($site['prefix']);
		$dishes = $dishObj->findAll();
		$dishesList = [];
		$content = "";
		$fields = [ 'id', 'image', 'name', 'category', 'price', 'Edit', 'Delete' ];
		$datas = [];
		$dishCatObj = new DishCategory($site['prefix']);

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
		$dishObj = new Dish($site['prefix']);

		$dishCatObj = new DishCategory($site['prefix']);
		$dishCategories = $dishCatObj->findAll();
		$dishCatArr = [];
		
		if($dishCategories){
			foreach($dishCategories as $item){
				$dishCatArr[$item['id']] = $item['name'];
			}
		}

		$view = new View('createDish', 'back', $site);
		$view->assign("categories", $dishCatArr);
		$view->assign('pageTitle', "Add a dish");

		if(!empty($_POST) ) {
			$errors = [];

			[ "name" => $name, "description" => $description, "price" => $price, "category" => $dishCat, "notes" => $notes, "allergens" => $allergens ] = $_POST;
			[ "image" => $image ] = $_FILES;

			if(!empty($name) && !is_null($name)){

				if(isset($image) && !empty($image) && $image['size'] != 0){

					$imgDir = "/uploads/cms/" . $site['subDomain'] . "/dishes/";
					$imgName = $site['subDomain'] . '_' . preg_replace("/\s+/", "_", $name);
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
			header("Location: dishes");
			exit();
		}

		$dishObj = new Dish($site['prefix']);
		$dishObj->setId($_GET['id']??0);
		$dish = $dishObj->findOne();
		if(!$dish){
			header("Location: dishes");
			exit();
		}

		$dishCatObj = new DishCategory($site['prefix']);
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

		$view = new View('createDish', 'back', $site);
		$view->assign("categories", $dishCatArr);
		$view->assign("name", $dish['name']);
		$view->assign("image", (DOMAIN . '/' . $dish['image']));
		$view->assign("notes", $dish['notes']);
		$view->assign("allergens", $dish['allergens']);
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

	public function deleteDishAction($site){
		try{
			if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('Dish not set'); }
			$dishObj = new Dish($site['prefix']);
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
		$dishObj = new Dish($site['prefix']);
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
					'name' => $dish['name'],
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
	* returns html for pageRenderer
	*/

	//$site is an instance of Site
	public function renderDishAction($site){
		if(!isset($_GET['id']) || empty($_GET['id']) ){
			return 'dish id not set ';
		}

		$dishObj = new Dish($site->getPrefix());
		$dishObj->setId($_GET['id']);
		$dishObj->setIsActive(1);
        $dish = $dishObj->findOne();
        if(!$dish){
            return 'No content found :/';
        }

		$view = new View('dish', 'front', $site);
		$view->assign('pageTitle', 'Dishes available');
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('dish', $dish);
	}

}