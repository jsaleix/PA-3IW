<?php

namespace App\Models;

use App\Core\Database;
use App\Core\FileUploader;
use App\Core\Security;

use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Dish_Category;


class Site extends Model
{

	protected $id = null;
	protected $name;
	protected $description;
	protected $image;
	protected $creator;
	protected $subDomain;
	protected $prefix;
    protected $type;
    protected $theme;
    protected $creationDate;
    protected $address;
    protected $phoneNumber;
    protected $emailPro;
    protected $instagram;
    protected $facebook;
    protected $twitter;
    private $invalidDomains = [ 'public', 'uploads', 'assets' ];

	public function __construct(){
		parent::__construct();
	}

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setCreationDate($creationDate){
        $this->creationDate = $creationDate;
    }

    public function getCreationDate(){
        return $this->creationDate;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    public function getEmailPro()
    {
        return $this->emailPro;
    }

    public function setEmailPro($emailPro)
    {
        $this->emailPro = $emailPro;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getTwitter()
    {
        return $this->twitter;
    }

    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    public function getInstagram()
    {
        return $this->instagram;
    }

    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    public function setName($name)
    {
        $this->name = htmlspecialchars($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = htmlspecialchars($description);
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getSubDomain()
    {
        return $this->subDomain;
    }

    public function setSubDomain($subDomain)
    {
        $this->subDomain = trim(mb_strtolower($subDomain));
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = htmlspecialchars($type);
    }

    public function getInvalidDomains(){
        return $this->invalidDomains;
    }
    public function returnData() : array{
		return get_object_vars($this);
	}

    //Overrides database->delete methode
    public function delete(){
        $sqlFiles = array(
            'dish_category', 'dish', 'booking','booking_settings', 'booking_planning', 
            'booking_planning_data', 'page', 'medium', 'post', 'content', 'comment', 
            'menu', 'menu_dish_association', 'post_medium_association'
        );

        foreach($sqlFiles as $key => $value)
        {
            $sqlFiles[$key] = $this->getPrefix() . '_' . ucfirst($value);
        }
        $sqlFiles = array_reverse($sqlFiles);
        if( $this->deleteTables($sqlFiles)){
            FileUploader::renameCMSDir($this->getSubDomain());
            return parent::delete();
        }else{
            return false;
        }

    }

    public function formCreate(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"edit-site col-5 col-sm-12",
                "submit"=>"Create",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"name",
                    "class"=>"input input-100",
                    "placeholder"=>"Website name",
                    "error"=>"The name cannot be empty!",
                    "required"=>true,
					"value"=> $this->name
                ],
				"description"=>[ 
					"type"=>"text",
					"placeholder"=>"Description",
					"id"=>"description",
					"class"=>"input input-100",
                    "error"=>"The description cannot be empty!",
					"required"=> false,
					"value"=> $this->description
                ],
                "type"=>[ 
					"type"=>"text",
					"label"=>"type",
					"id"=>"type",
					"class"=>"input input-100",
                    "placeholder"=>"Restaurant type",
                    "error"=>"The type cannot be empty!",
					"required"=> false,
					"value"=> $this->type,
                ],
                "subDomain"=>[ 
					"type"=>"text",
					"label"=>"domain of the site",
					"id"=>"subDomain",
					"class"=>"input input-100",
                    "error"=>"The domain cannot be empty!",
					"required"=> true,
					"value"=> $this->subDomain,
                ],
            ]
        ];
    }

    public function formThemeEdit($themes){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "class"=>"col-10",
                "submit"=>"Change theme",
                "submitClass"=>"btn btn-100 btn-light"
            ],
            "inputs"=>[
                "theme"=>[
                    "type"=>"select",
                    "class"=>"input input-100 input-select",
                    "options"=>$themes
                ]
            ]
        ];
    }

    public function formDelete(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "class"=>"col-10",
                "submit"=>"Delete this site",
                "submitClass"=>"btn btn-100 btn-light"
            ],
            "inputs"=>[
                "_method"=>[
                    "type"=>"hidden",
                    "value"=> 'delete'
                ],
            ]
        ];
    }

    public function formContactEdit(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "class"=>"col-10 form-90",
                "submit"=>"Update contacts",
                "submitClass"=>"btn btn-100 btn-light"
            ],
            "inputs"=>[
                "phoneNumber"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Phone Number",
                    "value"=> $this->phoneNumber
                ],
                "action"=>[
                    "type"=>"hidden",
                    "value"=>"contact"
                ],
                "emailPro"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Email",
                    "value"=> $this->emailPro
                ],
                "address"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Restaurant address",
                    "value"=> $this->address
                ],
            ]
        ];
    }

    public function formSocialEdit(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "class"=>"col-10 form-90",
                "submit"=>"Update socials",
                "submitClass"=>"btn btn-100 btn-light"
            ],
            "inputs"=>[
                "action"=>[
                    "type"=>"hidden",
                    "value"=>"socials"
                ],
                "instagram"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Instagram (Account link)",
                    "value"=>$this->instagram
                ],
                "twitter"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Twitter (Account link)",
                    "value"=>$this->twitter
                ],
                "facebook"=>[
                    "type"=>"text",
                    "class"=>"input input-100 input-select",
                    "placeholder"=>"Facebook (Page link)",
                    "value"=>$this->facebook
                ],
            ]
        ];
    }

    public function formEdit(){
        return [

            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"edit-site col-5 col-sm-12",
                "submit"=>"Apply",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
                "enctype"=>"multipart/form-data",
            ],
            "inputs"=>[
                "name"=>[ 
                    "type"=>"text",
                    "label"=>"Name",
                    "minLength"=>2,
                    "maxLength"=>45,
                    "id"=>"name",
                    "class"=>"input input-100",
                    "placeholder"=>"Website name",
                    "error"=>"The name cannot be empty!",
                    "required"=>true,
					"value"=> $this->name
                ],
				"description"=>[ 
					"type"=>"text",
					"placeholder"=>"Description",
					"id"=>"description",
					"class"=>"input input-100",
                    "error"=>"The description cannot be empty!",
					"required"=> false,
					"value"=> $this->description
                ],
                "type"=>[ 
					"type"=>"text",
					"label"=>"type",
					"id"=>"type",
					"class"=>"input input-100",
                    "placeholder"=>"Restaurant food type",
                    "error"=>"The type cannot be empty!",
					"required"=> false,
					"value"=> $this->type,
                ],
				"image"=>[ 
					"type"=>"file-img",
					"label"=>"New banner",
					"id"=>"image",
					"class"=>"input-file",
                    "error"=>"",
					"required"=> false,
					"value"=> $this->image
                ],
                "subDomain"=>[ 
					"type"=>"text",
					"label"=>"subDomain",
					"id"=>"subDomain",
					"class"=>"input input-100",
                    "error"=>"The subDomain cannot be empty!",
					"required"=> false,
					"value"=> $this->subDomain,
                    "disabled" => true
                ],
                "creationDate"=>[ 
					"type"=>"text",
					"label"=>"creationDate",
					"id"=>"creationDate",
                    "placeholder"=>"Creation date",
					"class"=>"input input-100",
                    "error"=>"The creationDate cannot be empty!",
					"required"=> false,
					"value"=> $this->creationDate,
                    "disabled" => true
                ],
            ]
        ];
    }
    

}




