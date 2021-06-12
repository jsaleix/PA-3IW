<?php

namespace CMS\Models;
use App\Core\Database;

class Menu_dish_association extends Database
{

	protected $id;
	protected $dish;
	protected $menu;

	public function __construct (){
		parent::__construct();
	}

	public function setPrefix($prefix){
		parent::setTableName($prefix.'_');
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setDish($dish){
		$this->dish = $dish;
	}

	public function getDish(){
		return $this->dish;
	}

	public function setMenu($menu){
		$this->menu = $menu;
	}

	public function getMenu(){
		return $this->menu;
	}

}




