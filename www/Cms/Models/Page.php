<?php

namespace CMS\Models;
use App\Core\Database;

class Page extends Database
{

	private $id = null;
	private $name;
	private $category = null;
    private $creationDate = null;

	public function __construct( $name, $category = null ){
		$this->setName($name);
        $this->setCategory($category);
	}

	/**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
        // double action de peupler l'objet avec ce qu'il y a en bdd
        // https://www.php.net/manual/fr/pdostatement.fetch.php
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $lastname
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function formRegister(){
        
    }



}




