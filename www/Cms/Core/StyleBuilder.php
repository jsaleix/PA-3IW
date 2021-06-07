<?php

namespace CMS\Core;
use CMS\Models\Page;

class StyleBuilder
{

	public function __construct(){
	}

    public static function renderStyle($site){
		/* Check a future style table */
        return self::renderDefaultStyle();
	}

	public static function renderDefaultStyle(){
		$style = "<style>";
        $style .= "
        ul{
            display: flex;
            flexDirection: row;
            list-style-type: none;
        }

        li{
            margin-right: 10px;
        }
        ";
        $style .= "</style>";
        return $style;
	}

}






