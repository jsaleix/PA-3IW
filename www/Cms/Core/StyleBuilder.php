<?php

namespace CMS\Core;

class StyleBuilder
{

	public function __construct(){
	}

    public static function renderStyle($site){
        $styleLink = DOMAIN."/Assets/cms/Front/Default/Styles/main.css";

        $theme = "Default";

        if(gettype($site) == "array"){
            $theme = $site['theme'];
        }else if(gettype($site) === "object"){
            $theme = $site->getTheme();
        }
        $theme = strlen($theme)>0 || is_null($theme) ? $theme : "Default";

		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/CMS/Views/Front/" . $theme . "/Styles/main.css"))
            $styleLink = DOMAIN."/Assets/cms/Front/" . $theme . "/Styles/main.css";
        
        return '<link rel="stylesheet" href="'.$styleLink.'">';
	}

    public static function renderPersonnalStyles($site){
        $css = "";

        $allStyles = json_decode($site->getStyles());

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }


        foreach($allStyles as $class=>$styles){
            $css .= ".".$class."{\n";
            foreach($styles as $styleName=>$style){
                $css .= $styleName.":".$style.";\n";
            }
            $css .= "\n}";
        }
       
        return $css;

    }

    public static function renderStyleScript($site){
        $styleScriptLink = DOMAIN."/Assets/cms/Front/Default/Styles/main.js";

        $theme = "Default";

        if(gettype($site) == "array"){
            $theme = $site['theme'];
        }else if(gettype($site) === "object"){
            $theme = $site->getTheme();
        }
        $theme = strlen($theme)>0 || is_null($theme) ? $theme : "Default";

		if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/public/Assets/Cms/Front/" . $theme . "/Styles/main.js"))
            $styleScriptLink = DOMAIN."/Assets/cms/Front/" . $theme . "/Styles/main.js";
        
        return '<script src="'.$styleScriptLink.'"></script>';
	}

}






