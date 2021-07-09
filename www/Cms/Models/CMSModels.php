<?php

namespace CMS\Models;
use App\Models\Model;

class CMSModels extends Model
{
    public function __construct ($prefix = null){
		parent::__construct();
        if($prefix != null){
            parent::setTableName($prefix."_");
        }
	}

    public function populate($data, $save = FALSE){
        foreach($data as $key => $attr){
            if(property_exists($this, $key)){
                $key = "set".ucfirst($key);
                if(method_exists($this, $key))
                    $this->$key($attr);
            }
        }
        if($save == TRUE)
            return $this->save();
        return;
    }

    public function edit(&$data){
        foreach($data as $key => $attr){
            if(property_exists($this, $key)){
                $getter = "get".ucfirst($key);
                $setter = "set".ucfirst($key);
                if(method_exists($this, $getter) && $attr == $this->$getter()){
                    $this->$setter(null);
                    unset($data[$key]);
                }
            }
        }
        if(count($data) > 0 )
            return $this->populate($data, TRUE);
        else 
            return;
    }
}