<?php

namespace App\Models;

use App\Core\Database;

use CMS\Models\Page;
use CMS\Models\Content;

class Action extends Database
{

	protected $id;
	protected $name;
	protected $controller;
	protected $method;

	public function __construct(){
		parent::__construct();
	}

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

}




