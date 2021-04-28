<?php

namespace App\Models;

use App\Core\Database;

class MailToken extends Database
{
    private $id = null;
    protected $token;
    protected $userId;
    protected $expiresDate;

    public function __construct(){
        parent::__construct();
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of expiresDate
     */ 
    public function getExpiresDate()
    {
        return $this->expiresDate;
    }

    /**
     * Set the value of expiresDate
     *
     * @return  self
     */ 
    public function setExpiresDate($expiresDate)
    {
        $expiresDate->modify('+7 day');
        $this->expiresDate = $expiresDate->format("Y-m-d H:i:s");

        return $this;
    }
}