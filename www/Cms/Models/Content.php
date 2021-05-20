<?php

namespace CMS\Models;
use App\Core\Database;

class Content extends Database
{

	protected $id;
	protected $title;
	protected $content;
	protected $page;
	protected $publisher;
	protected $type = 'article';
	protected $publicationDate;

	public function __construct ($title, $content, $page, $publisher ){
		parent::__construct();
		$this->setTitle($title);
		$this->setContent($content);
        $this->setPage($page);
		$this->setPublisher($publisher);
	}

	public function setTableName($prefix){
		parent::setTableName($prefix.'_');
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setTitle($title){
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

	public function setPage($page){
		$this->page = $page;
	}

	public function getPage(){
		return $this->page;
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

	public function getPublicationDate($publicationDate){
		return $this->publicationDate;
	}

	public function returnData() : array{
		return get_object_vars($this);
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
                    "required"=>true
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
                    "required"=>true
                ],
                "page"=>[ 
                    "type"=>"select",
                    "label"=>"Page associated",
                    "id"=>"page",
                    "class"=>"input-page_select",
                    "error"=>"A page needs to be associated with your article!",
                    "required"=>true,
					"options" => [
						"1" => "test1"
					]
                ]
            ]
        ];
    }
	
}




