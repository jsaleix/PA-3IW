<?php

namespace CMS\Models;

class Dish extends CMSModels
{

	protected $id;
	protected $name;
	protected $image;
	protected $description;
	protected $price;
	protected $category;
	protected $notes;
	protected $allergens;
	protected $isActive;

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
		$name = $name;
        //$name = preg_replace("/\s+/", "", $name);//removes spaces
        // $name = preg_replace("/[^A-Za-z0-9\s]+/", "", $name);//keeps letters and digits
		$name = preg_replace("/\'+/", "\\'", $name);//keeps letters and digits
		
        $this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

    public function setImage($image){
		if(gettype($image) == 'string'){
			$this->image = $image;
		}
	}

	public function getImage(){
		return $this->image;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setPrice($price){
		$this->price = $price;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setCategory($category){
        if($category === '0'){ $category = 'IS NULL'; }
		$this->category = $category;
	}

	public function getCategory(){
		return $this->category;
	}

    public function setNotes($notes){
		$this->notes = $notes;
	}

	public function getNotes(){
		return $this->notes;
	}

    public function setAllergens($allergens){
		$this->allergens = $allergens;
	}

	public function getAllergens(){
		return $this->allergens;
	}

    public function setIsActive($isActive){
        if(!$isActive){ $isActive = 1; }

		$this->isActive = $isActive;
	}

	public function getIsActive(){
		return $this->isActive;
	}

	public function render(){
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

	public function formAdd(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Add",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
				"enctype"=>"multipart/form-data"
            ],
            "inputs"=>[
				"image"=>[ 
                    "type"=>"file-img",
                    "label"=>"image",
                    "id"=>"image",
                    "class"=>"input-file",
                    "required"=>true,
                ],
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New dish",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
                ],
				"description"=>[ 
					"type"=>"text",
					"id"=>"description",
					"class"=>"input-description",
                    "placeholder"=>"Description here",
                ],
                "price"=>[ 
					"type"=>"text",
					"label"=>"price",
					"id"=>"price",
					"class"=>"input-price",
                    "required"=>true,
                    "placeholder"=>"Price",
                ],
                "category"=>[ 
					"type"=>"select",
					"label"=>"Category associated",
					"id"=>"category",
					"class"=>"input-category-select",
                ],
                "notes"=>[ 
					"type"=>"text",
					"label"=>"notes",
					"id"=>"notes",
					"class"=>"input-notes",
                    "placeholder"=>"Notes",
                ],
                "allergens"=>[ 
					"type"=>"text",
					"label"=>"allergens",
					"id"=>"allergens",
					"class"=>"input-allergens",
                    "placeholder"=>"Allergens",
                	]
				]
				/*, 
				"render" => [
					"block1" => [ "price", "category" ]
				]*/

        ];
    }

	public function formEdit($categoryArr = null){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
				"enctype"=>"multipart/form-data"
            ],
            "inputs"=>[
				"image"=>[ 
                    "type"=>"file",
                    "label"=>"image",
                    "id"=>"image",
                    "class"=>"input-file",
                    "required"=>false,
					"value"=> $this->image
                ],
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"title",
                    "class"=>"input-content",
                    "placeholder"=>"New dish",
                    "error"=>"The title cannot be empty!",
                    "required"=>true,
					"value"=> $this->name
                ],
                
				"description"=>[ 
					"type"=>"text",
					"label"=>"Description",
					"id"=>"description",
					"class"=>"input-description",
					//"options" => $categoryArr,
					"value"=> $this->description
                ],
                "price"=>[ 
					"type"=>"text",
					"label"=>"price",
					"id"=>"price",
					"class"=>"input-price",
					"options" => $categoryArr,
					"value"=> $this->price
                ],
                "category"=>[ 
					"type"=>"select",
					"label"=>"Category associated",
					"id"=>"category",
					"class"=>"input-category-select",
					"options" => $categoryArr,
					"value"=> $this->category
                ],
                "notes"=>[ 
					"type"=>"text",
					"label"=>"notes",
					"id"=>"notes",
					"class"=>"input-notes",
					"options" => $categoryArr,
					"value"=> $this->notes
                ],
                "allergens"=>[ 
					"type"=>"text",
					"label"=>"allergens",
					"id"=>"allergens",
					"class"=>"input-allergens",
					"options" => $categoryArr,
					"value"=> $this->allergens
                ]
            ]
        ];
    }

	public function listFormalize($data){
        return [
            "config"=>[
                "method"=>"",
                "action"=>"",
				"href" => "dish/edit?id=" . $data['id'],
                "id"=>"form_content",
                "class"=>"inline-list",
                "submit"=>"Edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "fields"=>[
				"image"=>[ 
					"type"=>"image",
                    "value" => $data['image']??'image',
					"name" => $data['image']
                ],
                "name"=>[ 
                    "type"=>"text",
                    "value" => $data['name'],
					"name" => $data['name']
                ],
                "description"=>[ 
                    "type"=>"text",
                    "value" => $data['description']??'description',
					"name" => $data['description']
                ],
				"price"=>[ 
                    "type"=>"text",
                    "value" => $data['price']??'price',
					"name" => $data['price']
                ],
				"category"=>[ 
                    "type"=>"text",
                    "value" => $data['category'],
					"name" => $data['category']
                ],
				"notes" => [
					"type"=>"text",
                    "value" => $data['notes']??'notes',
					"name" => $data['notes']
                ],
                "allergens" => [
					"type"=>"text",
                    "value" => $data['allergens']??'allergens',
					"name" => $data['allergens']
				],
                "isActive" => [
					"type"=>"text",
                    "value" => $data['isActive']??'is active',
					"name" => $data['isActive']
				]
            ]
        ];
    }

	public function returnData() : array{
		return get_object_vars($this);
	}
	
}




