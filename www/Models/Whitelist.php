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
}