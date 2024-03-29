<?php

namespace App\Models;

class User extends Model
{

	protected $id = null;
	protected $firstname;
	protected $lastname;
	protected $email;
	protected $pwd;
    protected $avatar;
	protected $role;
	protected $isActive;
    protected $token;

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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $name
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $surname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $mail
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $password
     */
    public function setPwd($password)
    {
        $this->pwd = $password;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $password
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return int
     */
    public function getIsActive(): int
    {
        return $this->isActive;
    }

    /**
     * @param int $idDeleted
     */
    public function setIsActive(int $isActive)
    {
        $this->isActive = $isActive;
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

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $surname
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getFullName(){
        return $this->firstname . ' ' . $this->lastname;
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
                    "placeholder"=>"Prénom",
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
                    "placeholder"=>"Nom de famille",
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
                    "placeholder"=>"Email",
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

    public function formEdit(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"",
                "class"=>"col-4 form-account",
                "submit"=>"Apply",
                "submitClass"=>"btn btn-100",
                "enctype" => "multipart/form-data"
            ],
            "inputs"=>[
                "firstname"=>[ 
                    "type"=>"text",
                    "label"=>"Your first name",
                    "minLength"=>2,
                    "maxLength"=>55,
                    "id"=>"firstname",
                    "class"=>"input input-100",
                    "placeholder"=>"Example: John",
                    "error"=>"Your name length must be between 2 and 55 characters",
                    "required"=>true,
                    "value"=> $this->getFirstname()
                ],
                "lastname"=>[ 
                    "type"=>"text",
                    "label"=>"Your last name",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"lastname",
                    "class"=>"input input-100",
                    "placeholder"=>"Example: Doe",
                    "error"=>"Your last name length must be between 2 and 255 characters",
                    "required"=>true,
                    "value"=> $this->getLastname()
                ],
                "email"=>[ 
                    "type"=>"email",
                    "label"=>"Your mail",
                    "minLength"=>8,
                    "maxLength"=>320,
                    "id"=>"email",
                    "class"=>"input input-100",
                    "placeholder"=>"Example: name@gmail.com",
                    "error"=>"Your mail must be between 8 and 320 characters",
                    "required"=>true,
                    "value"=> $this->getEmail()
                ],
                "avatar"=>[ 
					"type"=>"file-img",
					"label"=>"Avatar",
                    "name"=>"avatar",
					"id"=>"avatar",
					"class"=>"input-file",
                    "error"=>"",
					"required"=> false,
					"value"=> $this->getAvatar()
                ],
            ]

        ];
    }

    public function formAdminEdit($roleValues){
        $form = $this->formEdit();
        $roleInput =  [
                "type"=>"select",
                "label"=>"Role",
                "id"=>"role",
                "class"=>"input-role-select",
                "value" => $this->role,
                "options" => $roleValues
        ];
        $form['inputs']['role'] = $roleInput;
        return $form;
    }

    public function formPwd(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_pwd",
                "class"=>"form-pwd",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "oldPwd"=>[ 
                    "type"=>"password",
                    "label"=>"Your current password",
                    "minLength"=>8,
                    "id"=>"pwd",
                    "class"=>"input-auth",
                    "placeholder"=>"Your current password",
                    "error"=>"Your password must be at least 8 characters",
                    "required"=>true
                ],
                "pwd"=>[ 
                    "type"=>"password",
                    "label"=>"New password",
                    "minLength"=>8,
                    "id"=>"pwd",
                    "class"=>"input-auth",
                    "placeholder"=>"New password",
                    "error"=>"Your password must be at least 8 characters",
                    "required"=>true
                ],
                "pwdConfirm"=>[ 
                    "type"=>"password",
                    "label"=>"Confirmation",
                    "confirm"=>"pwd",
                    "id"=>"pwdConfirm",
                    "class"=>"input-auth",
                    "placeholder"=>"New password confirmation",
                    "error"=>"Your confirmation password does not match",
                    "required"=>true
                ]
            ]
        ];
    }


}




