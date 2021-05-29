<?php

namespace App\Core;

class FileUploader
{

	public static function createCMSDirs($name){
		$cmsRoot = '/uploads/cms/'. $name;
		mkdir($_SERVER['DOCUMENT_ROOT']. $cmsRoot);
		mkdir($_SERVER['DOCUMENT_ROOT']. $cmsRoot . '/dishes');
		mkdir($_SERVER['DOCUMENT_ROOT']. $cmsRoot . '/library');
	}

	public static function uploadImage($file, $name, $dir){
		var_dump($file);
		if(!isset($file)){ 	
			echo 'not set';		
			return false;
		}
		
		$target_dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
		$imageFileType = strtolower(pathinfo(($target_dir . basename($file["name"])),PATHINFO_EXTENSION));
		$target_file = $target_dir . $name . '.' . $imageFileType;

		$check = getimagesize($file['tmp_name']);
		if(!$check) {
			echo 'check false';		
			return false;
		} 

		if ($file["size"] > 500000) {
			return false;
		}

		if( $imageFileType != 'jpg' && $imageFileType != 'jpeg' && $imageFileType != 'png'){ 
			return false;
		}

		if(!is_dir($target_dir)){ 
			return false;
		}
		$addingFile = move_uploaded_file($file["tmp_name"], $target_file);
		$publicDir = str_replace('/var/www/html/', '', $target_file);
		return $addingFile ? $publicDir : false;
	}


}