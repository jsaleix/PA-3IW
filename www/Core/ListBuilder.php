<?php

namespace App\Core;

class ListBuilder
{

	public static function render($list){
		$html = '';

        foreach($list as $key)
        {
            $html .= "<form 
            method='".($key["config"]["method"]??"GET")."' 
            id='".($key["config"]["id"]??"")."' 
            class='".($key["config"]["class"]??"")."' 
            action='".($key["config"]["action"]??"")."'>";

            $fields = $key['fields'];
            foreach($fields as $field => $config){

                if($config["type"] == "select"){
                    $html .= self::renderSelect($field, $config);
                }else if($config["type"] == "checkbox"){
                        self::renderCheckBox($field, $config);
                }else if($config["type"] == "input"){
                        $html .= self::renderInput($field, $config);
                }else{
                        $html .= '<p id=' . '>' . $config['value'] . '</p>';
                }

            }
            $html .= "<button class='".($form["config"]["submitClass"]??"")."' >";
            $html .= "<a href='". ($key["config"]["href"]??"/"). "'>" . ($key["config"]["submit"]??"Go") ."</a>";
            $html .= "</button>";
            $html .= "</form>";
        }

		echo $html;

	}


	public static function renderInput($name, $configInput){
		return "<input 
						name='".$name."' 
						type='".($configInput["type"]??"text")."'
						id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'
						placeholder='".($configInput["placeholder"]??"")."'
						".(!empty($configInput["required"])?"required='required'":"")."
					>";
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