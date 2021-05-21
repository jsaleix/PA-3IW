<?php

namespace CMS\Models;
use App\Core\Database;

class Page extends Database
{

	protected $id = null;
	protected $name;
	protected $category = null;
    protected $creationDate = null;

	public function __construct( $name, $tablePrefix, $category = null ){
        parent::__construct($tablePrefix.'_');
		$this->setName($name);
        $this->setCategory($category);
	}

    public function setTableName($prefix){
		parent::setTableName($prefix.'_');
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

    public function formAddContent($categories){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Create",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Title",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New page",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
                ],
				"category"=>[ 
					"type"=>"select",
					"label"=>"Page associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $categories
					]
                ]
        ];
    }

    public function listFormalize($pageData){
        return [
            "config"=>[
                "method"=>"",
                "action"=>"",
				"href" => "editPage?id=" . $pageData['id'],
                "id"=>"form_content",
                "class"=>"inline-list",
                "submit"=>"Edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "fields"=>[
                "name"=>[ 
                    "type"=>"text",
                    "value" => $pageData['name']
                ],
				"category"=>[ 
					"type"=>"text",
                    "value" => $pageData['category']
                ],
                "creator"=>[ 
                    "type"=>"text",
                    "value" => $pageData['creator']
                ],
                "creationDate"=>[ 
                    "type"=>"text",
                    "value" => $pageData['creationDate']
                ],
            ]
        ];
    }

    public function formEditContent($content, $dataArr){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Title",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New article",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
					"value"=> $content['name']
                ],
				"category"=>[ 
					"type"=>"select",
					"label"=>"Page associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $dataArr,
					"value"=> $content['category']
					]
            ]
        ];
    }

}




