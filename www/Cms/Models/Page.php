<?php

namespace CMS\Models;
use CMS\Models\Content;

use App\Core\Database;
use App\Models\Action;


class Page extends Database
{

	protected $id = null;
	protected $name;
	protected $category = null;
    protected $creationDate = null;
    private $action = null;

	public function __construct(){
        parent::__construct();
	}

    public function setPrefix($prefix){
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
        if($name === 'admin' || $name === 'ent') $name.='_';
        $name = htmlspecialchars($name);
        $name = preg_replace("/\s+/", "", $name);
        $this->name = htmlspecialchars($name);
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

    public function setAction($action){
        $this->action = $action;
    }

    public function getAction(){
        return $this->action;
    }

    public function save(){
        if($this->action){
            //Verify if action exists
            $actionObj = new Action();
            $actionObj->setId($this->action);
            $checkAction = $actionObj->findOne();
            if(!$checkAction){
                return false;
            }
        }else{
            $this->action = 1;
        }

        $page =  parent::save();
        $content = true;

        if(empty($this->id)){
            $pageObj = new self();
            $pageObj->setName($this->name);
            $pageObj->setPrefix(parent::getPrefix());
            $page = $pageObj->findOne();

            $contentObj = new Content();
            $contentObj->setPrefix(parent::getPrefix());
            $contentObj->setPage($page['id']);
            $contentObj->setMethod($this->action);
            $content = $contentObj->save();
        }else{
            $contentObj = new Content();
            $contentObj->setPrefix(parent::getPrefix());
            $contentObj->setPage($this->id);

            $contentId = $contentObj->findOne();
            $contentObj->setId($contentId['id']);
            $contentObj->setMethod($this->action);
            $content = $contentObj->save();
        }

        if($page && $content){
            return true;
        }else{
            return false;
        }
    }

    public function formAddContent($actionArr){
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
				"action"=>[ 
					"type"=>"select",
					"label"=>"action associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"An action needs to be associated with your page!",
					"required"=>true,
					"options" => $actionArr
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
                "action" => [
                    "type" => "text",
                    "value" => $pageData['action']??'Action unknown'
                ]
            ]
        ];
    }

    public function formEditContent($content, $dataArr, $actionArr = null){
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
					"class"=>"input-page-select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $dataArr,
					"value"=> $content['category']
                ],
                empty($actionArr) ? null :"action"=>[
                    "type"=>"select",
					"label"=>"Action associated",
					"id"=>"action",
					"class"=>"input-action-select",
					"error"=>"An action needs to be associated with your page!",
					"required"=>true,
					"options" => $actionArr,
					"value"=> $content['action']
                ]
            ]
        ];
    }

}




