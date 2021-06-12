<?php

namespace CMS\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Action;

use CMS\Models\Dish;
use CMS\Models\Menu;

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
				$button = '<a href="editMenu?id=' . $item['id'] . '">Go</a>';
				$datas[] = "'".$item['id']."','".$item['name']."','".$item['description']."','".$item['notes']. "','" . $button . "'";
			}
		}

		$addCatButton = ['label' => 'Create a new menu', 'link' => 'createmenu'];
		
		$view = new View('back/list', 'back');
		$view->assign("navbar", NavbarBuilder::renderNavBar($site, 'back'));
		$view->assign("createButton", $addCatButton);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage your menus");
	}

    public function editMenusAction($site){

    }


		


}