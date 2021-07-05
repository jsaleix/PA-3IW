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
}