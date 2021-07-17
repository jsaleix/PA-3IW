<?php

namespace CMS\Core;

class StyleBuilder
{

	public function __construct(){
	}

    public static function renderStyle($site){
        $styleLink = DOMAIN."/Cms/Views/Front/Default/Styles/main.css";

        $theme = "Default";

        if(gettype($site) == "array"){
            $theme = $site->getTheme();
        }else if(gettype($site) === "object"){
            $theme = $site->getTheme();
        }
        $theme = strlen($theme)>0 || is_null($theme) ? $theme : "Default";

		if(file_exists("CMS/Views/Front/" . $theme . "/Styles/main.css"))
            $styleLink = DOMAIN."/Cms/Views/Front/" . $theme . "/Styles/main.css";
        
        return '<link rel="stylesheet" href="'.$styleLink.'">';
	}

    public static function renderStyleScript($site){
        $styleScriptLink = DOMAIN."/Cms/Views/Front/Default/Styles/main.js";

        $theme = "Default";

        if(gettype($site) == "array"){
            $theme = $site->getTheme();
        }else if(gettype($site) === "object"){
            $theme = $site->getTheme();
        }
        $theme = strlen($theme)>0 || is_null($theme) ? $theme : "Default";

		if(file_exists("CMS/Views/Front/" . $theme . "/Styles/main.js"))
            $styleScriptLink = DOMAIN."/Cms/Views/Front/" . $theme . "/Styles/main.js";
        
        return '<script src="'.$styleScriptLink.'"></script>';
	}

}






