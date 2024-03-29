<?php

namespace CMS\Models;

class Menu extends CMSModels
{

	protected $id;
	protected $name;
	protected $description;
	protected $notes;
	protected $isActive;

	public function setPrefix($prefix){
		parent::setTableName($prefix.'_');
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setName($name){
        $name = $name;
        $name = preg_replace("/[^A-Za-z0-9\s]+/", "", $name);

        $this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getDescription(){
		return $this->description;
	}

    public function setNotes($notes){
		$this->notes = $notes;
	}

	public function getNotes(){
		return $this->notes;
	}

    public function setIsActive($isActive){
        if(!$isActive){ $isActive = 1; }

		$this->isActive = $isActive;
	}

	public function getIsActive(){
		return $this->isActive;
	}

	public function formAdd(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-col col-8",
                "submit"=>"Add",
                "submitClass"=>"btn btn-100"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input input-100",
                    "placeholder"=>"Menu name",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
                ],
				"description"=>[ 
					"type"=>"textarea",
					"id"=>"description",
					"class"=>"input input-100",
                    "placeholder"=>"Menu description",
                ],
                "notes"=>[ 
					"type"=>"text",
					"label"=>"notes",
					"id"=>"notes",
					"class"=>"input input-100",
                    "placeholder"=>"Notes about the menu",
                ]
            ]
        ];
    }

	public function formEdit(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"form-col",
                "submit"=>"Apply",
                "class" => "",
                "submitClass"=>"btn btn-100"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input input-100",
                    "placeholder"=>"New dish",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
					"value"=> $this->name
                ],
				"description"=>[ 
					"type"=>"textarea",
					"placeholder"=>"Description",
					"id"=>"description",
					"class"=>"input input-100",
					"value"=> $this->description
                ],
                "notes"=>[ 
					"type"=>"text",
					"placeholder"=>"Notes",
					"id"=>"notes",
					"class"=>"input input-100",
					"value"=> $this->notes
                ],
                "action"=>[
                    "type"=>"hidden",
                    "value"=>"apply"
                ]
            ]
        ];
    }

	public function listFormalize($data){
        return [
            "config"=>[
                "method"=>"",
                "action"=>"",
				"href" => "editDish_Category?id=" . $data['id'],
                "id"=>"form_content",
                "class"=>"input",
                "submit"=>"Edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "fields"=>[
                "name"=>[ 
                    "type"=>"text",
                    "value" => $data['name'],
					"name" => $data['name']
                ],
                "description"=>[ 
                    "type"=>"text",
                    "value" => $data['description']??'description',
					"name" => $data['description']
                ],
				"notes" => [
					"type"=>"text",
                    "value" => $data['notes']??'notes',
					"name" => $data['notes']
                ],
                "isActive" => [
					"type"=>"text",
                    "value" => $data['isActive']??'is active',
					"name" => $data['isActive']
				]
            ]
        ];
    }
	
}




