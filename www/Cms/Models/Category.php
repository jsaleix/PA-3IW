<?php

namespace CMS\Models;

class Category extends CMSModels
{

	protected $id;
	protected $name;
	protected $description;
	protected $creator;
	protected $creationDate;

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

	public function setDescription($description){
		$this->description = $description;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setCreator($creator){
		$this->$creator = $creator;
	}

	public function getCreator(){
		return $this->creator;
	}

	public function setCreationDate($creationDate){
		$this->creationDate = $creationDate;
	}

	public function getCreationDate(){
		return $this->creationDate;
	}

	public function renderCategory(){
		/*switch($this->type){
			case 'article':
				extract(get_object_vars($this));
				echo '<h1>' . $title . '</h1>';
				echo '<p>' . $publisher . '</p>';
				echo '<p>' . $content . '</p>';
				echo '<hr>';
				break;

			default: 
			return;
		}*/
	}

	public function formAddCategory(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Publish",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New category",
                    "error"=>"The category name cannot be empty!",
                    "required"=>true,
                ],
                "description"=>[ 
                    "type"=>"text",
                    "label"=>"Description",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"content",
                    "class"=>"input-content",
                    "placeholder"=>"Let\'s describe this category.",
                    "error"=>"A description is required for an article!",
                    "required"=>true
                ],
            ]
        ];
    }

	public function formEditContent($content, $pagesArr){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "title"=>[ 
                    "type"=>"text",
                    "label"=>"Title",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New article",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
					"value"=> $content['title']
                ],
                "content"=>[ 
                    "type"=>"text",
                    "label"=>"Content",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"content",
                    "class"=>"input-content",
                    "placeholder"=>"Let's write something here",
                    "error"=>"A content is required for an article!",
                    "required"=>true,
					"value"=> $content['content']
                ],
				"page"=>[ 
					"type"=>"select",
					"label"=>"Page associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $pagesArr,
					"value"=> $content['page']
					]
                ]
        ];
    }

	public function listFormalize($data){
        return [
            "config"=>[
                "method"=>"",
                "action"=>"",
				"href" => "editArticle?id=" . $data['id'],
                "id"=>"form_content",
                "class"=>"inline-list",
                "submit"=>"Edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "fields"=>[
                "title"=>[ 
                    "type"=>"text",
                    "value" => $data['title']??$data['title'],
					"name" => $data['title']
                ],
				"content"=>[ 
					"type"=>"text",
                    "value" => $data['content']??'content',
					"name" => $data['content']
                ],
                "page"=>[ 
                    "type"=>"text",
                    "value" => $data['page']??'page',
					"name" => $data['page']
                ],
				"publicationDate"=>[ 
                    "type"=>"text",
                    "value" => $data['publicationDate']??'publicationDate',
					"name" => $data['publicationDate']
                ],
				"type"=>[ 
                    "type"=>"text",
                    "value" => $data['type'],
					"name" => $data['type']
                ],
				"publisher" => [
					"type"=>"text",
                    "value" => $data['publisher'],
					"name" => $data['publisher']
				]
            ]
        ];
    }
	
}




