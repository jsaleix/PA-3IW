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
}