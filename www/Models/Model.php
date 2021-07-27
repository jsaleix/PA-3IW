<?php

namespace App\Models;
use App\Core\Database;

class Model extends Database
{
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

    public function findOne($fill = FALSE){
        $result = parent::findOne();
        if( $result && $fill){
            $this->populate($result);
        }
        return $result;
    }
}