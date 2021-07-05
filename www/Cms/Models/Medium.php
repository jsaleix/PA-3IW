<?php

namespace CMS\Models;

class Medium extends CMSModels
{
    protected $id;
    protected $name;
    protected $type;
    protected $path;
    protected $publisher;
    protected $publicationDate;

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

	public function setName($name){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

    public function setType($type){
        $this->type = $type;
    }

    public function getType(){
        return $this->type;
    }

    public function setPath($path){
        $this->path = $path;
    }

    public function getPath(){
        return $this->path;
    }

    public function setPublisher($publisher){
        $this->publisher = $publisher;
    }

    public function getPublisher(){
        return $this->publisher;
    }

    public function setPublicationDate($publicationDate){
        $this->publicationDate = $publicationDate;
    }

    public function getPublicationDate(){
        return $this->publicationDate;
    }

    public function formAdd(){
        return [
            "config"=>[
                "config"=>[
                    "method"=>"POST",
                    "action"=>"",
                    "id"=>"form_content",
                    "class"=>"form-content",
                    "submit"=>"Publish",
                    "submitClass"=>"cta-blue width-80 last-sm-elem"
                ],
            ],
            "inputs"=>[
                "name"=>[
                    
                ],
                "type"=>[

                ],
                "path"=>[

                ]
            ]
        ];
    }
}