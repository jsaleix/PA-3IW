<?php

namespace App\Core;

class FormValidator
{


	public static function check($form, $data, $files = null){
		if( $files != null)
			$data = array_merge($data, $files);
		$errors = [];
		if( count($data) == count($form["inputs"])){
			foreach ($form["inputs"] as $name => $configInput) {
				if(!empty($configInput["required"]) &&
					$configInput["required"] == true &&
					empty($data[$name])
				){
					$errors[] = "Le champ \'".$configInput["label"]."\' doit être renseigné";
					return $errors;
				}

				if($configInput["type"] == "file" ){
					if( !self::verifyFileSize($data[$name])){
						$errors[] = "Le fichier est trop gros";
						return $errors;
					}
					if( !self::verifyFileType($data[$name])){
						$errors[] = "Le fichier doit etre de type png, jpg ou jpeg";
						return $errors;
					}
					break;			
				}
				$data[$name] = self::sanitizeData($data[$name]);


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
		return htmlspecialchars(stripslashes(trim($data)));
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
		if($file["size"] > 500000)
			return false;
		return true;
	}
	public static function verifyFileType($file){
		$type = explode("/", $file["type"]);
		if( $type[1] != "png" && $type[1] != "jpg" && $type[1] != "jpeg")
			return false;
		return true;
	}

}

