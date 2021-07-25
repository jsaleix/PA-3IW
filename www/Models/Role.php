<?php

namespace App\Models;

class Role extends Model
{
    protected $id;
    protected $name;
    protected $description;
    protected $icon;
    protected $isAdmin;
    
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

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param mixed $mail
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function formEdit(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"role_form",
                "class"=>"form",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
                "enctype"=>"multipart/form-data"
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
                    "value"=> $this->name

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
                    "value"=>  $this->description

                ],
                "icon"=>[ 
                    "type"=>"file-img",
					"label"=>"Role icon",
					"id"=>"icon",
					"class"=>"input-file",
                    "error"=>"",
					"required"=> false,
                    "value"=> $this->icon

                ],
                "isAdmin"=>[
                    "type"=>"radio",
                    "label"=>"Has admin privileges ?",
                    "minLength"=>1,
                    "maxLength"=>1,
                    "options" => [
                        0 => "no",
                        1 => "yes"
                    ],
                    "class"=>"input-content",
                    "error"=>"You need to specify the privileges",
                    "required"=>false,
                    "value"=> intval($this->isAdmin)
                ]
            ]
        ];
    }

    public function formCreate(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"role_form",
                "class"=>"form",
                "submit"=>"Create",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
                "enctype"=>"multipart/form-data"
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
                ],
                "icon"=>[ 
                    "type"=>"file-img",
					"label"=>"Role icon",
					"id"=>"icon",
					"class"=>"input-file",
                    "error"=>"",
					"required"=> false,
                ],
                "isAdmin"=>[
                    "type"=>"radio",
                    "label"=>"Has admin privileges ?",
                    "minLength"=>1,
                    "maxLength"=>1,
                    "options" => [
                        0 => "no",
                        1 => "yes"
                    ],
                    "class"=>"input-content",
                    "error"=>"You need to specify the privileges",
                    "required"=>false,
                ]
            ]
        ];
    }

}




