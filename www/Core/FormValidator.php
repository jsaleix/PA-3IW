<?php

namespace App\Core;

class FormValidator
{


	public static function check($form, &$data){
		$errors = [];
		if( $data["CSRF"] != $_SESSION["CSRF"] ){
			$errors[] = "Le site a rencontré un problème de sécurité, vérifiez bien utiliser notre site officiel";
			return $errors;
		}
		unset($data["CSRF"]);
		foreach ($form["inputs"] as $name => $configInput) {
			if( !empty($configInput["disabled"])){
				unset($form["inputs"][$name]);
			}
		}
		if( count($data) == count($form["inputs"])){
			foreach ($form["inputs"] as $name => $configInput) {
				if($configInput["type"] == "radio" &&
					isset($data[$name])
				){
					if($data[$name] == 0){
						$data[$name] = "IS FALSE";
					}
					continue;
				}

				if(!empty($configInput["required"]) &&
					$configInput["required"] == true &&
					empty($data[$name])
				){
					$errors[] = "Le champ \'".$configInput["label"]."\' doit être renseigné";
					return $errors;
				}

				if($configInput["type"] == "file" || $configInput["type"] == "file-img"){
					if( $configInput["required"] == true ){
						if( !self::verifyFileSize($data[$name]) && !empty($data[$name]["name"])){
							$errors[] = "Le fichier est trop gros";
						}
						if( !self::verifyFileType($data[$name])){
							$errors[] = "Le fichier doit etre de type png, jpg ou jpeg";
						}
						continue;
					} else if( !empty($data[$name]["name"]) ) {
						if( !self::verifyFileSize($data[$name])){
							$errors[] = "Le fichier est trop gros";
						}
						if( !self::verifyFileType($data[$name])){
							$errors[] = "Le fichier doit etre de type png, jpg ou jpeg";
						}
						continue;
					} else{
						continue;
					}
				}
				$data[$name] = self::sanitizeData($data[$name]);

				if((empty($configInput["required"]) || $configInput["required"] == false) && strlen($data[$name]) == 0){
					continue;
				}

				if(!empty($configInput["minLength"]) &&
					is_numeric($configInput["minLength"]) &&
					strlen($data[$name]) < $configInput["minLength"]
					){
					$errors[] = $configInput["label"]."\' doit faire plus de ". $configInput["minLength"] . " caractères";
				}

				if(!empty($configInput["maxLength"]) && 
					is_numeric($configInput["maxLength"]) &&
					strlen($data[$name]) > $configInput["maxLength"]
					){
						$errors[] = $configInput["label"]."\' doit faire moins de ". $configInput["maxLength"] . " caractères";

					}
				
				
				if( $configInput["type"] == "number" &&
					!empty($configInput["max"]) && 
					is_numeric($configInput["max"]) &&
					$data[$name] > $configInput["max"]){
						$errors[] = "Le maximum pour le champ ".$configInput["label"]." acceptée est de ".$configInput["max"];
					}

				if($configInput["type"] == "email" && 
					!self::emailValidate($data[$name])
				){
					$errors[] = $configInput["error"];
				}

				if($configInput["type"] == "password" &&
					!self::pwdValidate($data[$name]) &&
					$configInput["label"] != "Confirmation"
				){
					$errors[] = $configInput["error"];
				}

				if( !empty($configInput["confirm"]) &&
					$data[$name] != $data[$configInput["confirm"]]
				){
					$errors[] = $configInput["error"];
				}

				if($configInput["type"] == "date" &&
					self::validateDate($data[$name])
					){	
						$date = date('Y-m-d', strtotime($data[$name]));
						if(!empty($configInput["min"])
						){
							$min = date('Y-m-d', strtotime($configInput["min"]));
							if( $date < $min ){
								$errors[] = $configInput["error"];
							}
						}
						if(!empty($configInput["max"])
						){
							$max = date('Y-m-d', strtotime($configInput["max"]));
							if($date > $max){
								$errors[] = $configInput["error"];
							}
						}
				} else if($configInput["type"] == "date" &&
							!self::validateDate($data[$name])
				){
					$errors[] = "Votre date est pas au bon format";
				}

			}
		}else{
			$errors[] = "Tentative de Hack";
		}
		return $errors;
		
	}

	public static function sanitizeData($data){
		return filter_var(trim($data), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	}

	public static function emailValidate($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function pwdValidate($pwd){
		return preg_match("^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$^", $pwd);
	}

	public static function validateDate($date, $format = 'Y-m-d'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	public static function verifyFileSize($file){
		if($file["size"] > 20000000)
			return false;
		return true;
	}
	public static function verifyFileType($file){
		$type = explode("/", $file["type"]);
		if(!isset($type[1])){ 
			return false; 
		}
		if( $type[1] != "png" && $type[1] != "jpg" && $type[1] != "jpeg")
			return false;
		return true;
	}

}

