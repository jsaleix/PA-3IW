<?php

namespace CMS\Models;
use App\Core\Database;

class CMSModels extends Database
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
                if($attr == $this->$getter()){
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