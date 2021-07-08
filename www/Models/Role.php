<?php

namespace App\Models;

class Role extends Model
{
    protected $id;
    protected $name;
    protected $description;
    protected $icon;
    
	public function __construct(){
		parent::__construct();
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
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $surname
     */
    public function setDescription($description)
    {
        if($description === '0'){ $description = 'IS NULL'; }
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $mail
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function formEdit($data){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"role_form",
                "class"=>"form",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Role name",
                    "minLength"=>2,
                    "maxLength"=>55,
                    "id"=>"name",
                    "class"=>"input",
                    "placeholder"=>"Anything",
                    "error"=>"The role name must be between 2 and 55 caracters",
                    "required"=>true,
                    "value"=> $data['name']

                ],
                "description"=>[ 
                    "type"=>"text",
                    "label"=>"Description",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"description",
                    "class"=>"input",
                    "placeholder"=>"Description",
                    "required"=>false,
                    "value"=> $data['description']

                ],
                "icon"=>[ 
                    "type"=>"file-img",
					"label"=>"Role icon",
					"id"=>"icon",
					"class"=>"input-file",
                    "error"=>"",
					"required"=> false,
                    "value"=> $data['icon']

                ],
            ]
        ];
    }

}




