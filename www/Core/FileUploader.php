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
		try{
			if(!isset($file)){ 	
				return false;
			}
			
			$target_dir = $_SERVER['DOCUMENT_ROOT'] . $dir;
			$imageFileType = strtolower(pathinfo(($target_dir . basename($file["name"])),PATHINFO_EXTENSION));
			$target_file = $target_dir . $name . '.' . $imageFileType;
	
			if(!$file['tmp_name']){
				throw new \Exception('No name');
			}
	
			$check = getimagesize($file['tmp_name']);
			if(!$check) {
				throw new \Exception('No size');
			} 
	
			if ($file["size"] > 5000000) {
				throw new \Exception('File too large');
			}
	
			if( $imageFileType != 'jpg' && $imageFileType != 'jpeg' && $imageFileType != 'png'){ 
				throw new \Exception('Incorrect file extension');
			}
	
			if(!is_dir($target_dir)){ 
				throw new \Exception('Wrong directory ' . $target_dir);
			}
		}catch(\Exception $e){
			//echo $e->getMessage();
			return false;
		}
		
		$addingFile = move_uploaded_file($file["tmp_name"], $target_file);
		$publicDir = str_replace('/var/www/html/', '', $target_file);
		return $addingFile ? $publicDir : false;
	}


}