<?php

namespace CMS\Models;

class Post_medium_association extends CMSModels
{

    protected $id;
    protected $post;
    protected $medium;

    public function __construct ($prefix = null){
		parent::__construct($prefix);
	}

    public function setPrefix($prefix){
		parent::setTableName($prefix.'_');
	}

    public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

    public function setPost($post){
        $this->post = $post;
    }

    public function getPost(){
        return $this->post;
    }

    public function setMedium($medium){
        $this->medium = $medium;
    }

    public function getMedium(){
        return $this->medium;
    }

}