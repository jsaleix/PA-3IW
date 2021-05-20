<?php

namespace App\Core;

class FormBuilder
{

	public static function render($form){
		
		$html = "<form 
				method='".($form["config"]["method"]??"GET")."' 
				id='".($form["config"]["id"]??"")."' 
				class='".($form["config"]["class"]??"")."' 
				action='".($form["config"]["action"]??"")."'>";
		
		if($form["config"]["class"] === "form-auth"){
			if($form["config"]["id"] === "form_register")
			$html .= '<h2 style="font-weight:lighter">Inscription</h2>';
			else
				$html .= '<h2 style="font-weight:lighter">Connexion</h2>';
		}

		foreach ($form["inputs"] as $name => $configInput) {

			if($configInput["type"] == "select"){
				$html .= self::renderSelect($name, $configInput);
			}
			else{
				if($configInput["type"] == "checkbox"){
					if($form["config"]["class"] === "form-auth"){
						$html .= '<legend style="width: 80%; margin-top:0;"><a href="#" id="forgotpwd">Mot de passe oublié ?</a></legend>';
						$html .= '<div class="checkbox-container" style="width: 80%; margin-top: 5;">'.
							self::renderCheckBox($name, $configInput)
						.'</div>';
					}else{
						self::renderCheckBox($name, $configInput);
					}
				}else
					$html .= self::renderInput($name, $configInput);
			}

			
		}

		if($form["config"]["class"] === "form-auth")
			$html .= "<input class=\"".($form["config"]["submitClass"]??"")."\"type='submit' value=\"".($form["config"]["submit"]??"Connexion")."\" />";
		else
			$html .= "<input class=\"".($form["config"]["submitClass"]??"")."\" type='submit' value=\"".($form["config"]["submit"]??"Valider")."\" />";

		$html .= "</form>";



		echo $html;

	}


	public static function renderInput($name, $configInput){
		return "<input 
						name='".$name."' 
						type='".($configInput["type"]??"text")."'
						id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'
						placeholder='".($configInput["placeholder"]??"")."'". 
						(!empty($configInput["required"])?"required='required'":"") .
						"value='" . ($configInput["value"]??"") . "'/>";
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


	public static function renderSelect($name, $configInput){
		$html = "<select name='".$name."' id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'>";


		foreach ($configInput["options"] as $key => $value) {
			$html .= "<option value='".$key."'>".$value."</option>";
		}

		$html .= "</select><br>";

		return $html;
	}

}