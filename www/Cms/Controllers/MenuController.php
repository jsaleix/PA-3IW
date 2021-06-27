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

        $view = new View('back/menu', 'back');

        if(!empty($_POST) && isset($_POST['action']) && !empty($_POST['action']) ){
            $action = $_POST['action'];
            $errors = [];

            $this->manageDishInMenu($action, $site, $view, $_POST, $_GET);
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
                        'id' => $dish['id'], 'image' =>  DOMAIN . '/' . $dish['image'], 'name' => $dish['name']
                    );
                }
            }
        }

		//$form = $menuObj->formEdit((array)$menu);
		$view->assign("navbar", navbarBuilder::renderNavBar($site, 'back'));
		//$view->assign("form", $form);
        $view->assign("name", $menu['name']);
        $view->assign("description", $menu['description']);
        $view->assign("notes", $menu['notes']);
        $view->assign("categories", $selectDishCat);
		$view->assign('pageTitle', "Edit your menu");
        $view->assign('subDomain', $site['subDomain']);
        $view->assign('dishes', $dishesArr);



    }

    public function manageDishInMenu($action, $site, $viewObj, $_postFields, $_getFields ){
        $menuObj = new Menu();
		$menuObj->setPrefix($site['prefix']);
		$menuObj->setId($_GET['id']??0);
		$menu = $menuObj->findOne();
        
        $dishMenuAssocObj = new Menu_dish_association();
        $dishMenuAssocObj->setPrefix($site['prefix']);
        $dishMenuAssocObj->setMenu($menu['id']);

        $dishMenuAssocObj = new Menu_dish_association();
        $dishMenuAssocObj->setPrefix($site['prefix']);
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
                        $viewObj->assign("message", $message);
                    }else{
                        $errors[] = "Cannot update this menu";
                        $viewObj->assign("errors", $errors);
                    }
                }
                break;

            case 'add_dish':
                [ "menu" => $menu, "dish" => $dish] = $_postFields;
                $dishMenuAssocObj->setPrefix($site['prefix']);
                $dishMenuAssocObj->setMenu($menu);
                $dishMenuAssocObj->setDish($dish);
                $dishMenuId = $dishMenuAssocObj->findOne();
                if($dishMenuId !== false){ return; }
                $adding = $dishMenuAssocObj->save();
                if($adding){
                    $message ='Dish successfully added!';
                    $viewObj->assign("message", $message);
                }else{
                    $errors[] = "Cannot update this menu";
                    $viewObj->assign("errors", $errors);
                }
                break;

            case 'remove_dish':
                [ "dish" => $dish] = $_postFields;
                [ "id" => $menu] = $_getFields;

                $dishMenuAssocObj->setPrefix($site['prefix']);
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


	//CMS FRONT
    /*
	* Front vizualization
	* returns html for pageRenderer
	*/
    public function renderMenus($site, $filter = null){
		$menuObj = new Menu();
        $menuObj->setPrefix($site->getPrefix());
        $menus = $menuObj->findAll();
        $html = "";
        if(!$menus || count($menus) === 0){
            $html .= 'No menu found :/';
            return;
        }
        foreach($menus as $menu)
        {
            $html .= '<h2><a href="ent/menu?id=' . $menu['name'] . '"/>##########MENU ' . $menu['name'] . '</a></h2>' ;
            $dishMenuAssocObj = new Menu_dish_association();
            $dishMenuAssocObj->setPrefix($site->getPrefix());
            $dishMenuAssocObj->setMenu($menu['id']);
            $dishes = $dishMenuAssocObj->findAll();
            if($dishes){
                foreach($dishes as $dish)
                {
                    $html .= $this->renderDishItem($dish['dish'], $site->getPrefix());
                }
            }
            $html .= '<hr>';

        }

		$view = new View('cms', 'front');
		$view->assign('pageTitle', 'Posts');
		$view->assign("navbar", NavbarBuilder::renderNavbar($site->returnData(), 'front'));
		$view->assign("style", StyleBuilder::renderStyle($site->returnData()));
		$view->assign('content', $html);
	}

    public function renderDishItem($dishId, $sitePrefix){
        $dishObj = new Dish();
        $dishObj->setPrefix($sitePrefix);
        $dishObj->setId($dishId);
        $dish = $dishObj->findOne();
        if(!$dish){ return; }

		$html = '<h2><a href="ent/dish?id='. $dish['id'] . '">' . $dish['name'] . '</a></h2>';
        $html .= '<img src=' . DOMAIN . '/' . $dish['image'] . ' width=100 height=80/>';
		$html .= '<br>';

        return $html;
	}


}