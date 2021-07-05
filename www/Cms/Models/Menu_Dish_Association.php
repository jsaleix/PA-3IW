<?php

namespace CMS\Models;

class Menu_dish_association extends CMSModels
{

	protected $id;
	protected $dish;
	protected $menu;

	public function __construct ($prefix = null){
		parent::__construct($prefix);
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




