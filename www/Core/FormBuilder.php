<?php

namespace App\Core;

class FormBuilder
{

	public static function render($form){
		
		$html = "<form 
				method='".($form["config"]["method"]??"GET")."' 
				id='".($form["config"]["id"]??"")."' 
				class='".($form["config"]["class"]??"")."' 
				action='".($form["config"]["action"]??"") . "'
				name='".($form["config"]["name"]??"") . "'";

		$html .= !empty($form["config"]['enctype'])? ("enctype='" . $form["config"]['enctype'] . "'" ) : "";
		$html .= ">";
		
		if($form["config"]["class"] === "form-auth"){
			if($form["config"]["id"] === "form_register")
			$html .= '<h2 style="font-weight:lighter">Inscription</h2>';
			else
				$html .= '<h2 style="font-weight:lighter">Connexion</h2>';
		}

		foreach ($form["inputs"] as $name => $configInput) {

			switch($configInput["type"]){
				case "radio":
					$html .= self::renderRadio($name, $configInput);
					break;

				case "select":
					$html .= self::renderSelect($name, $configInput);
					break;

				case "checkbox":
					if($form["config"]["class"] === "form-auth"){
						$html .= '<legend style="width: 80%; margin-top:0;"><a href="#" id="forgotpwd">Mot de passe oublié ?</a></legend>';
						$html .= '<div class="checkbox-container" style="width: 80%; margin-top: 5;">'.
							self::renderCheckBox($name, $configInput)
						.'</div>';
					}else{
						self::renderCheckBox($name, $configInput);
					}
					break;

				case 'file':
					$html .= self::renderFileInput($name, $configInput);
					break;

				case 'file-img':
					$html .= self::renderFileImgInput($name, $configInput);
					break;

				case 'textarea':
					$html .= self::renderTextarea($name, $configInput);
					break;

				default:
					$html .= self::renderInput($name, $configInput);


			}
			
		}

		$html .= self::createCSRFToken();

		if($form["config"]["class"] === "form-auth")
			$html .= "<input class=\"".($form["config"]["submitClass"]??"")."\"type='submit' value=\"".($form["config"]["submit"]??"Connexion")."\" />";
		else
			$html .= "<input class=\"".($form["config"]["submitClass"]??"")."\" type='submit' value=\"".($form["config"]["submit"]??"Valider")."\" />";

		$html .= "</form>";



		echo $html;

	}

	public static function createCSRFToken(){
		$CSRFtoken = bin2hex(random_bytes(54));
		$_SESSION["CSRF"]=$CSRFtoken;
		$html = "<input
					name=\"CSRF\"
					type=\"hidden\"
					id=\"CSRF\"
					style=\"visibility: hidden\"
					value=\"".$CSRFtoken."\">";
		return $html;
		
	}


	public static function renderInput($name, $configInput){
		$html =  "<input 
						name=\"".$name."\" 
						type=\"".($configInput["type"]??"text")."\" 
						id=\"".($configInput["id"]??"")."\" 
						class=\"".($configInput["class"]??"")."\" 
						placeholder=\"".($configInput["placeholder"]??"")."\" ". 
						(!empty($configInput["required"])?"required=\"required\"":"") .
						(!empty($configInput["disabled"])?"disabled":"").
						(!empty($configInput["min"])?"min=\"".$configInput["min"]."\" ":"").
						(!empty($configInput["max"])?"max=\"".$configInput["max"]."\" ":"").
						" value=\"" . htmlspecialchars($configInput["value"]??"") . "\" />";
		return $html;
	}

	public static function renderTextarea($name, $configInput){
		$html =  "<textarea
						name=\"".$name."\" 
						type=\"".($configInput["type"]??"text")."\" 
						id=\"".($configInput["id"]??"")."\" 
						class=\"".($configInput["class"]??"")."\" 
						placeholder=\"".($configInput["placeholder"]??"")."\" ". 
						(!empty($configInput["required"])?"required=\"required\"":"") .
						(!empty($configInput["disabled"])?"disabled":"").
						(!empty($configInput["min"])?"min=\"".$configInput["min"]."\" ":"").
						(!empty($configInput["max"])?"max=\"".$configInput["max"]."\" ":"").
						">".htmlspecialchars($configInput["value"]??"")."</textarea>";
		return $html;
	}

	public static function renderFileInput($name, $configInput){

		$html =  "<input 
						name=\"".$name."\" 
						type=\"".($configInput["type"]??"text")."\" 
						id=\"".($configInput["id"]??"")."\" 
						class=\"".($configInput["class"]??"")."\" 
						placeholder=\"".($configInput["placeholder"]??"")."\" ". 
						(!empty($configInput["required"])?"required=\"required\"":"") .
						(!empty($configInput["disabled"])?"disabled":"").
						" value=\"" . ($configInput["value"]??"") . "\" />";
		if(!empty($configInput["type"]) && $configInput["type"] === 'file' && !empty($configInput["value"])){
			if(strpos($configInput['value'], 'http') === false ){
				$html .= '<img src="'. DOMAIN . '/'. $configInput['value'] .'" height="100"  />';
			}else{
				$html .= '<img src="'. $configInput['value'] .'" height="100"  />';

			}
		}
		return $html;
	}


	public static function renderFileImgInput($name, $configInput){

		$html =  "<div class='input-banner-container'>
					<input 
						name=\"".$name."\" 
						type=\"file\" 
						id=\"".($configInput["id"]??"")."\" 
						class=\"".($configInput["class"]??"")."\" 
						placeholder=\"".($configInput["placeholder"]??"")."\" ". 
						(!empty($configInput["required"])?"required=\"required\"":"") .
						(!empty($configInput["disabled"])?"disabled":"").
						" value=\"" . ($configInput["value"]??"") . "\" />";

		if(!empty($configInput["type"]) && $configInput["type"] === 'file-img' && !empty($configInput["value"])){
			if(strpos($configInput['value'], 'http') === false ){

				$html .= '<img src="'. DOMAIN . '/'. $configInput['value'] .'"/>';

			}else{

				$html .= '<img src="'. $configInput['value'] .'"/>';

			}
		}elseif(!empty($configInput["type"]) && $configInput["type"] === 'file-img' && (empty($configInput["value"]) || is_null($configInput["value"]))){
			$html .= '<img src="'. DOMAIN . "/Assets/images/default.png" .'"/>';
		}

		$html .= '<label for="'.($configInput['id']??"").'">'.($configInput['label']??"").'</label>
				</div>';
				
		return $html;
	}

	public static function renderCheckBox($name, $configInput){
		return 
		'<input 
			style="outline:none;"
			type="checkbox"
			name="'.$name.'" 
			class="'.($configInput["class"]??"").'"
			id="'.($configInput["id"]??"").'" 
			>
    	<label class="'.($configInput["labelClass"]??"").'" for="'.($configInput["id"]??"").'">'.($configInput["label"]??"").'</label>';
	}

	public static function renderRadio($name, $configInput){
		$html = '<div class="radio">';
		foreach($configInput['options'] as $key => $value)
		{
			$html .= '<div class="radio-option">';
			if(isset($configInput['value']) && !is_null($configInput['value']) && ($key == $configInput['value'])){
				$html .= '<input type="radio" value="' . $key . '" name="' . $name . '" checked="checked"/>';
			}else{
				$html .= '<input type="radio" value="' . $key . '" name="' . $name . '"/>';
			}
			$html .= '<label class="'.($configInput["labelClass"]??"").'" for="'.($configInput["id"]??"").'">'.($value).'</label>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}


	public static function renderSelect($name, $configInput){
		$html = "<select onchange='".($configInput["script"]??"")."' name='".$name."' id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'>";

		foreach ($configInput["options"] as $key => $value) {
			if(!empty($configInput['value']) && $key === $configInput['value']){
				$html .= "<option value='".$key."' selected='selected'>".$value."</option>";
			}else{
				$html .= "<option value='".$key."'>".$value."</option>";
			}
		}

		$html .= "</select><br>";

		return $html;
	}

}