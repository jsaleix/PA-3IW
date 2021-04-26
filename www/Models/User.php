<?php

namespace App\Models;

use App\Core\Database;

class User extends Database
{

	private $id = null;
	protected $name;
	protected $surname;
	protected $mail;
	protected $password;
	protected $role = 0;
	protected $isActive = 0;

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
        // double action de peupler l'objet avec ce qu'il y a en bdd
        // https://www.php.net/manual/fr/pdostatement.fetch.php
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
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setEmail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->passwordd;
    }

    /**
     * @param mixed $password
     */
    public function setPwd($password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getIsDeleted(): int
    {
        return $this->isDeleted;
    }

    /**
     * @param int $idDeleted
     */
    public function setIsDeleted(int $isActive)
    {
        $this->isDeleted = $isActive;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole(int $role)
    {
        $this->role = $role;
    }


    public function formRegister(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_register",
                "class"=>"form-auth",
                "submit"=>"S'inscrire",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "firstname"=>[ 
                    "type"=>"text",
                    "label"=>"Votre prénom",
                    "minLength"=>2,
                    "maxLength"=>55,
                    "id"=>"firstname",
                    "class"=>"input-auth",
                    "placeholder"=>"Exemple: Yves",
                    "error"=>"Votre prénom doit faire entre 2 et 55 caractères",
                    "required"=>true
                ],
                "lastname"=>[ 
                    "type"=>"text",
                    "label"=>"Votre nom",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"lastname",
                    "class"=>"input-auth",
                    "placeholder"=>"Exemple: SKRZYPCZYK",
                    "error"=>"Votre nom doit faire entre 2 et 255 caractères",
                    "required"=>true
                ],
                "email"=>[ 
                    "type"=>"email",
                    "label"=>"Votre email",
                    "minLength"=>8,
                    "maxLength"=>320,
                    "id"=>"email",
                    "class"=>"input-auth",
                    "placeholder"=>"Exemple: nom@gmail.com",
                    "error"=>"Votre email doit faire entre 8 et 320 caractères",
                    "required"=>true
                ],
                "pwd"=>[ 
                    "type"=>"password",
                    "label"=>"Votre mot de passe",
                    "minLength"=>8,
                    "id"=>"pwd",
                    "class"=>"input-auth",
                    "placeholder"=>"Mot de passe",
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères",
                    "required"=>true
                ],
                "pwdConfirm"=>[ 
                    "type"=>"password",
                    "label"=>"Confirmation",
                    "confirm"=>"pwd",
                    "id"=>"pwdConfirm",
                    "class"=>"input-auth",
                    "placeholder"=>"Confirmation de mot de passe",
                    "error"=>"Votre mot de mot de passe de confirmation ne correspond pas",
                    "required"=>true
                ]
            ]

        ];
    }


    public function formLogin(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_login",
                "class"=>"form-auth",
                "submit"=>"Connexion",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "email"=>[ 
                    "type"=>"email",
                    "label"=>"",
                    "minLength"=>8,
                    "maxLength"=>320,
                    "id"=>"mail",
                    "class"=>"input-auth",
                    "placeholder"=>"Adresse email",
                    "error"=>"Votre email doit faire entre 8 et 320 caractères",
                    "required"=>true
                ],
                "pwd"=>[ 
                    "type"=>"password",
                    "label"=>"",
                    "minLength"=>8,
                    "id"=>"pwd",
                    "class"=>"input-auth",
                    "placeholder"=>"Mot de passe",
                    "error"=>"Votre mot de passe doit faire au minimum 8 caractères",
                    "required"=>true
                ],
                "remember"=>[
                    "type"=>"checkbox",
                    "id"=>"checkbox-auth",
                    "labelClass"=>"checkbox-label",
                    "label"=>"Se souvenir de moi"
                ]
            ]

        ];
    }


}




