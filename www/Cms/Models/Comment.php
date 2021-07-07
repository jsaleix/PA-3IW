<?php

namespace CMS\Models;

class Comment extends CMSModels
{

	protected $id = null;
	protected $message;
	protected $idPost;
    protected $idUser;
    protected $date;

    public function setPrefix($prefix){
		parent::setTableName($prefix.'_');
	}

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        // double action de peupler l'objet avec ce qu'il y a en bdd
        // https://www.php.net/manual/fr/pdostatement.fetch.php
    }

    public function getMessage()
    {
        return $this->message;
    }


    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getIdPost()
    {
        return $this->idPost;
    }


    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }


    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    public function getDate()
    {
        return $this->date;
    }


    public function setDate($date)
    {
        $this->date = $date;
    }

}




