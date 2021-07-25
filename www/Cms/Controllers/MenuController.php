<?php

namespace CMS\Controller;

use CMS\Models\Dish;
use CMS\Models\Menu;
use CMS\Models\Dish_Category;
use CMS\Models\Menu_dish_association;
use App\Core\Helpers as Helpers;
use CMS\Core\CMSView as View;
use CMS\Core\StyleBuilder;

class MenuController{


    public function manageMenusAction($site){
		$menuObj = new Menu($site->getPrefix());
		$menus = $menuObj->findAll();

		$addMenuButton = ['label' => 'Create a new menu', 'link' => 'menus/create'];
		
		$view = new View('menus', 'back', $site);
		$view->assign("createButton", $addMenuButton);
		$view->assign("menus", $menus);
		$view->assign('pageTitle', "Manage your menus");
	}

    public function editMenusAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'menu not set ';
			header("Location: /");
            exit();
		}

        $view = new View('menu', 'back', $site);

        if(!empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) ){
            $action = $_POST['action'];
            $errors = [];

            $this->manageDishInMenu($action, $site, $view, $_POST, $_GET);
		}

		$menuObj = new Menu($site->getPrefix());
		$menuObj->setId($_GET['id']??0);
		$menu = $menuObj->findOne();
		if(!$menu){
			header("Location: /");
            exit();
		}
		
        $dishCatObj = new Dish_Category($site->getPrefix());
        $dishCatArr = $dishCatObj->findAll();
        $selectDishCat = [];

        if($dishCatArr){
            foreach($dishCatArr as $item){
                $selectDishCat[$item['id']] = $item['name'];
            }
        }

        $dishMenuAssocObj = new Menu_dish_association($site->getPrefix());
        $dishMenuAssocObj->setMenu($menu['id']);
        $dishes = $dishMenuAssocObj->findAll();
        $dishesArr = [];
        if($dishes){
            foreach($dishes as $item){
                $dishObj = new Dish($site->getPrefix());
                $dishObj->setId($item['dish']);
                $dish = $dishObj->findOne();
                if($dish){
                    $dishesArr[] = array(
                        'id' => $dish['id'], 'image' =>  DOMAIN . '/' . $dish['image'], 'name' => $dish['name']
                    );
                }
            }
        }

		//$form = $menuObj->formEdit((array)$menu);
		//$view->assign("form", $form);
        $view->assign("name", $menu['name']);
        $view->assign("description", $menu['description']);
        $view->assign("notes", $menu['notes']);
        $view->assign("categories", $selectDishCat);
		$view->assign('pageTitle', "Edit your menu");
        $view->assign('subDomain', $site->getSubDomain());
        $view->assign('dishes', $dishesArr);

    }


    public function exportMenuAction($site){
        try{
            if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('Please set a menu'); }
            
            $menuObj = new Menu($site->getPrefix());
            $menuObj->setId($_GET['id']??0);
            $menu = $menuObj->findOne();

            if(!$menu){ throw new \Exception('Menu not found'); }

            // echo "Menu : " . $menu['name'];
            include_once($_SERVER['DOCUMENT_ROOT'].'/public/Assets/cms/Menus/Default/Default.php');

        }catch(\Exception $e){
            echo $e->getMessage();
			// \App\Core\Helpers::customRedirect('/admin/menus?error', $site);
        }
    }

    public function deleteMenuAction($site){
        try{
            if(!isset($_GET['id']) || empty($_GET['id']) ){ throw new \Exception('menu is not set'); }
            $menuObj = new Menu($site->getPrefix());
            $menuObj->setId($_GET['id']??0);
            $menu = $menuObj->findOne();
            if(!$menu){ throw new \Exception('Menu not found'); }
            $check = $menuObj->delete();
            if(!$check){ throw new \Exception('Cannot delete this menu');}
			\App\Core\Helpers::customRedirect('/admin/menus?success', $site);
        }catch(\Exception $e){
            echo $e->getMessage();
			\App\Core\Helpers::customRedirect('/admin/menus?error', $site);
        }
    }

    public function manageDishInMenu($action, $site, $viewObj, $_postFields, $_getFields ){
        $menuObj = new Menu($site->getPrefix());
		$menuObj->setId($_GET['id']??0);
		$menu = $menuObj->findOne();
        
        $dishMenuAssocObj = new Menu_dish_association($site->getPrefix());
        $dishMenuAssocObj->setMenu($menu['id']);

        $dishMenuAssocObj = new Menu_dish_association($site->getPrefix());
        $dishMenuAssocObj->setMenu($menu['id']);

        switch($action)
        {
            case 'apply':
                [ "name" => $name, "description" => $description, "notes" => $notes ] = $_postFields;

                if( $name){
                    //Verify the dishCategor submitted
                    $menuObj->setName($name);
                    $menuObj->setDescription($description);
                    $menuObj->setNotes($notes);
                    $menuObj->setIsActive($isActive??1);
    
                    $adding = $menuObj->save();
                    if($adding){
                        $message ='Menu successfully updated!';
					    $viewObj->assign("alert", Helpers::displayAlert("success",$message,3500));
                    }else{
                        $errors[] = "Cannot update this menu";
                        $viewObj->assign("errors", $errors);
                    }
                }
                break;

            case 'add_dish':
                [ "menu" => $menu, "dish" => $dish] = $_postFields;
                $dishMenuAssocObj->setPrefix($site->getPrefix());
                $dishMenuAssocObj->setMenu($menu);
                $dishMenuAssocObj->setDish($dish);
                $dishMenuId = $dishMenuAssocObj->findOne();
                if($dishMenuId !== false){ return; }
                $adding = $dishMenuAssocObj->save();
                if($adding){
                    $message ='Dish successfully added!';
					$viewObj->assign("alert", Helpers::displayAlert("success",$message,3500));
                }else{
                    $errors[] = "Cannot update this menu";
                    $viewObj->assign("errors", $errors);
                }
                break;

            case 'remove_dish':
                [ "dish" => $dish] = $_postFields;
                [ "id" => $menu] = $_getFields;

                $dishMenuAssocObj->setPrefix($site->getPrefix());
                $dishMenuAssocObj->setMenu($menu);
                $dishMenuAssocObj->setDish($dish);
                $dishMenuId = $dishMenuAssocObj->findOne();
                if($dishMenuId === false) return ;
                $dishMenuAssocObj->setId($dishMenuId['id']);
                $dishMenuAssocObj->delete();
                break;
        }
		
    }

    public function createMenuAction($site){
        $menuObj = new Menu($site->getPrefix());

		$form = $menuObj->formAdd();

		$view = new View('create', 'back', $site);
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
                    $message ='New menu successfully created!';
					$view->assign("alert", Helpers::displayAlert("success",$message,3500));

                    $id = $menuObj->getLastId();
                    if($id){
                        header('Location: '.DOMAIN . '/site/' . $site->getSubDomain() . '/admin/menus/edit?id=' . $id);
                        exit();
                    }
				}else{
					$errors[] = "Cannot create this menu";
					$view->assign("errors", $errors);
				}
			}
		}
    }


	//CMS FRONT
    /*
	* Front vizualization
	* returns html for pageRenderer
	*/
    public function renderMenus($site, $filter = null){
        $view = new View('menus', 'front', $site);
        $view->assign('pageTitle', 'Menus');
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));

		$menuObj = new Menu($site->getPrefix());
        $dishObj = new Dish($site->getPrefix());
        $dishCatObj = new Dish_Category($site->getPrefix());
        $dishMenuAssocObj = new Menu_dish_association($site->getPrefix());

        $menuData = [];
        
        $menus = $menuObj->findAll();
        try{
            if($menus && count($menus) > 0){
                foreach($menus as $menu)
                {
                    $dishes = [];
                    
                    $dishMenuAssocObj->setMenu($menu['id']);
                    $dishesInMenu = $dishMenuAssocObj->findAll();
                    if($dishesInMenu){
                        foreach($dishesInMenu as $dishId)
                        {
                            $dishObj->setId($dishId['dish']);
                            $dish = $dishObj->findOne();
                            if($dish){
                                if($dish['category']){
                                    $dishCatObj->setId($dish['category']);
                                    $category = $dishCatObj->findOne();
                                    if($category){
                                        $dish['category'] = $category['name'];
                                    }
                                }
                                $dishes[] = $dish;
                            }
                        }
                        $tmpMenu = ["menu" => $menu, "dishes" => $dishes] ;
                        $menuData[] = $tmpMenu;
                    }
                }
            }
        }catch(\Exception $e){
            echo $e->getMessage();
        }
		
		$view->assign('menus', $menuData);
	}

    public function renderMenuAction($site, $filter = null){
        $view = new View('menu', 'front', $site);
        try{
            $error = false;
            if($filter){
                $filter = json_decode($filter, true);
                if(isset($filter['menu'])){
                    $menuId = $filter['menu'];
                }else{
                    throw new \Exception('No menu set');
                }
            }else if(isset($_GET['id']) && !empty($_GET['id']) ){
                $menuId = $_GET['id'];
            }else{
                throw new \Exception('No id set');
            }

            $menuObj = new Menu();
            $dishMenuAssocObj = new Menu_dish_association();
            $dishCatObj = new Dish_Category();

            $dishesData = [];
            
            $menuObj->setPrefix($site->getPrefix());
            $menuObj->setId($menuId);
            $menu = $menuObj->findOne();


            if(!$menu){
                throw new \Exception('Menu not found');
            }
        }catch(\Exception $e){
            $view->assign('notFound', true);
            $view->assign('pageTitle', 'Not found');
            return; 
        }

        $dishMenuAssocObj->setPrefix($site->getPrefix());
        $dishMenuAssocObj->setMenu($menu['id']);
        $dishes = $dishMenuAssocObj->findAll();

        if($dishes){
            foreach($dishes as $dish)
            {
                $dishId = $dish['dish'];
                $sitePrefix = $site->getPrefix();
                $dishObj = new Dish($sitePrefix);
                $dishObj->setId($dishId);
                $dish = $dishObj->findOne();
                if($dish){
                    if($dish['category']){
                        $dishCatObj->setPrefix($sitePrefix);
                        $dishCatObj->setId($dish['category']);
                        $category = $dishCatObj->findOne();
                        if($category){
                            $dish['category'] = $category['name'];
                        }
                    }
                    $dishesData[] = $dish;
                }
            }
        }
		$view->assign('pageTitle', 'MENU ' . $menu['name']);
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
        $view->assign('menu', $menu);
		$view->assign('dishes', $dishesData);
        $view->assign('baseUrl', (DOMAIN . '/site/' . $site->getSubDomain()));
	}


}