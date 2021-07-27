<?php

namespace CMS\Models;

class Post extends CMSModels
{

	protected $id;
	protected $title;
	protected $content;
	protected $publisher;
	protected $type = 'article';
	protected $publicationDate;
	protected $allowComment;

	public function setPrefix($prefix){
		parent::setTableName($prefix.'_');
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setTitle($title){
		$title = $title;
		$title = preg_replace("/[^A-Za-z0-9 ]+/", "", $title);//keeps letters and digits

		$this->title = $title;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setContent($content){
		$this->content = $content;
	}

	public function getContent(){
		return $this->content;
	}

	public function setPublisher($publisher){
		$this->publisher = $publisher;
	}

	public function getPublisher(){
		return $this->publisher;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getType(){
		return $this->type;
	}

	public function setPublicationDate($publicationDate){
		$this->publicationDate = $publicationDate;
	}

	public function getPublicationDate(){
		return $this->publicationDate;
	}

	public function setAllowComment($allowComment){
		$this->allowComment = $allowComment == 0 ? 'IS FALSE' : 1;
	}

	public function getAllowComment(){
		return $this->allowComment;
	}

	public function returnData() : array{
		return get_object_vars($this);
	}

	public function getFields() : array{
		return array_keys(get_class_vars(get_class($this)));
	}

	public function renderContent(){
		switch($this->type){
			case 'article':
				extract(get_object_vars($this));
				echo '<h1>' . $title . '</h1>';
				echo '<p>' . $publisher . '</p>';
				echo '<p>' . $content . '</p>';
				echo '<hr>';
				break;

			default: 
			return;
		}
	}

	public function formAddContent(){
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
                ],
                "content"=>[ 
                    "type"=>"textarea",
                    "label"=>"Content",
                    "minLength"=>2,
                    "maxLength"=>255,
                    "id"=>"content",
                    "class"=>"input-content",
                    "placeholder"=>"Let's write something here",
                    "error"=>"A content is required for an article!",
                    "required"=>true
                ],
				"allowComment"=>[ 
                    "type"=>"radio",
                    "label"=>"Allow users to comment this post",
                    "minLength"=>1,
                    "maxLength"=>1,
					"options" => [
						0 => "disable comment",
						1 => "enable comment"
					],
                    "class"=>"input-content",
                    "placeholder"=>"Enable comments on the post",
                    "error"=>"You need to specify if the post can be commented!",
                    "required"=>true,
                ],
				/*"page"=>[ 
					"type"=>"select",
					"label"=>"Page associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $pagesArr
					]*/
                ]
        ];
    }

	public function formEditContent($content){
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
                    "type"=>"textarea",
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
				"allowComment"=>[ 
                    "type"=>"radio",
                    "label"=>"Allow users to comment this post",
                    "minLength"=>1,
                    "maxLength"=>1,
					"options" => [
						0 => "disable comment",
						1 => "enable comment"
					],
                    "class"=>"input-content",
                    "placeholder"=>"Enable comments on the post",
                    "error"=>"You need to specify if the post can be commented!",
                    "required"=>true,
					"value"=> $content['allowComment']
                ],
				/*"page"=>[ 
					"type"=>"select",
					"label"=>"Page associated",
					"id"=>"page",
					"class"=>"input-page_select",
					"error"=>"A page needs to be associated with your article!",
					"required"=>true,
					"options" => $pagesArr,
					"value"=> $content['page']
					]*/
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
                /*"page"=>[ 
                    "type"=>"text",
                    "value" => $data['page']??'page',
					"name" => $data['page']
                ],*/
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
				],
				"allowComment" => [
					"type"=>"text",
                    "value" => $data['allowComment'],
					"name" => $data['allowComment']
				],
            ]
        ];
    }
	
}




