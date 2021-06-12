<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Dish;
use CMS\Models\Menu;
use CMS\Models\DishCategory;
use CMS\Models\Menu_dish_association;

use CMS\Core\View;
use CMS\Core\NavbarBuilder;
use CMS\Core\StyleBuilder;

class MenuController{


    public function manageMenusAction($site){
		$menuObj = new Menu();
		$menuObj->setPrefix($site['prefix']);
		$menus = $menuObj->findAll();
		$fields = [ 'id', 'name', 'description', 'notes', 'edit'];
		$datas = [];

		if($menus){
			foreach($menus as $item){
				$button = '<a href="menus/edit?id=' . $item['id'] . '">Go</a>';
				$datas[] = "'".$item['id']."','".$item['name']."','".$item['description']."','".$item['notes']. "','" . $button . "'";
			}
		}

		$addCatButton = ['label' => 'Create a new menu', 'link' => 'menus/create'];
		
		$view = new View('back/list', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("createButton", $addCatButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage your menus");
	}

    public function editMenusAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'menu not set ';
			header("Location: /");
		}

		$menuObj = new Menu();
		$menuObj->setPrefix($site['prefix']);
		$menuObj->setId($_GET['id']??0);
		$menu = $menuObj->findOne();
		if(!$menu){
			header("Location: /");
		}
		
        $dishCatObj = new DishCategory();
        $dishCatObj->setPrefix($site['prefix']);
        $dishCatArr = $dishCatObj->findAll();
        $selectDishCat = [];

        if($dishCatArr){
            foreach($dishCatArr as $item){
                $selectDishCat[$item['id']] = $item['name'];
            }
        }

        $dishMenuAssocObj = new Menu_dish_association();
        $dishMenuAssocObj->setPrefix($site['prefix']);
        $dishMenuAssocObj->setMenu($menu['id']);
        $dishes = $dishMenuAssocObj->findAll();
        $dishesArr = [];
        if($dishes){
            foreach($dishes as $item){
                $dishObj = new Dish();
                $dishObj->setPrefix($site['prefix']);
                $dishObj->setId($item['dish']);
                $dish = $dishObj->findOne();
                if($dish){
                    $dishesArr[] = array(
                        'id' => $item['id'], 'image' =>  DOMAIN . '/' . $dish['image'], 'name' => $dish['name']
                    );
                }
            }
        }

		//$form = $menuObj->formEdit((array)$menu);

		$view = new View('back/menu', 'back');
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		//$view->assign("form", $form);
        $view->assign("name", $menu['name']);
        $view->assign("description", $menu['description']);
        $view->assign("notes", $menu['notes']);
        $view->assign("categories", $selectDishCat);
		$view->assign('pageTitle', "Edit your menu");
        $view->assign('subDomain', $site['subDomain']);
        $view->assign('dishes', $dishesArr);

		if(!empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) ) {
            $action = $_POST['action'];
            $errors = [];

            switch($action)
            {
                case 'apply':
                    [ "name" => $name, "description" => $description, "notes" => $notes ] = $_POST;

                    if( $name ){
                        //Verify the dishCategor submitted
                        $menuObj->setName($name);
                        $menuObj->setDescription($description);
                        $menuObj->setNotes($notes);
                        $menuObj->setIsActive($isActive??1);
        
                        $adding = $menuObj->save();
                        if($adding){
                            $message ='Menu successfully updated!';
                            $view->assign("message", $message);
                        }else{
                            $errors[] = "Cannot update this menu";
                            $view->assign("errors", $errors);
                        }
                    }
                    break;

                case 'add_dish':
                    var_dump($_POST);
                    [ "menu" => $menu, "dish" => $dish] = $_POST;
                    $dishMenuAssocObj->setPrefix($site['prefix']);
                    $dishMenuAssocObj->setMenu($menu);
                    $dishMenuAssocObj->setDish($dish);
                    $dishMenuId = $dishMenuAssocObj->findOne();
                    if($dishMenuId !== false){ return; }
                    $adding = $dishMenuAssocObj->save();
                    if($adding){
                        $message ='Menu successfully updated!';
                        $view->assign("message", $message);
                    }else{
                        $errors[] = "Cannot update this menu";
                        $view->assign("errors", $errors);
                    }
                    break;

                case 'remove_dish':
                    break;

            }
			
		}
    }

    public function createMenuAction($site){
        $menuObj = new Menu();
		$menuObj->setPrefix($site['prefix']);

		$form = $menuObj->formAdd();

		$view = new View('admin.create', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("form", $form);
		$view->assign('pageTitle', "Add a new menu");

		if(!empty($_POST) )
		{
			$errors = [];
			[ "name" => $name, "description" => $description, "notes" => $notes ] = $_POST;
			
			if( $name ){
				//Verify the dishCategor submitted
				$menuObj->setName($name);
				$menuObj->setDescription($description);
				$menuObj->setNotes($notes);
				$menuObj->setIsActive($isActive??1);

				$adding = $menuObj->save();
				if($adding){
                    $message ='Dish category successfully added!';
                    $view->assign("message", $message);
                    $id = $menuObj->getLastId();
                    if($id){
                        header('Location: '.DOMAIN . '/site/' . $site['subDomain'] . '/admin/menus/edit?id=' . $id);
                    }
				}else{
					$errors[] = "Cannot create this menu";
					$view->assign("errors", $errors);
				}
			}
		}
    }


		


}