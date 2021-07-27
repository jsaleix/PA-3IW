<?php

namespace App\Models;

use App\Core\Database;

class Whitelist extends Database
{

    protected $idUser;
    protected $idSite;

    public function __construct(){
		parent::__construct();
	}

    public function getId(){
        return null;
    }
    
    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($id)
    {
        $this->idUser = $id;
    }

    public function getIdSite()
    {
        return $this->idSite;
    }

    public function setIdSite($id)
    {
        $this->idSite = $id;
    }

    public function formAdd(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_add_user",
                "submit"=>"Ajouter",
                "class"=>""
            ],
            "inputs"=>[
                "user"=>[ 
                    "type"=>"number",
                    "label"=>"username",
                    "min"=>1,
                    "id"=>"user",
                    "placeholder"=>"Mail or name of the user you want to allow",
                    "error"=>"The user is invalid",
                    "required"=>true
                ],
            ]

        ];
    }

}