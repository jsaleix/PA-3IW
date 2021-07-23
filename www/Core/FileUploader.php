<?php

namespace App\Core;
use App\Core\ErrorReporter;

class FileUploader
{

	public static function createCMSDirs($name): bool{
		$cmsRoot = $_SERVER['DOCUMENT_ROOT']. '/public/uploads/cms/'. $name;
		if (mkdir($cmsRoot) &&
			mkdir($cmsRoot . '/dishes') &&
			mkdir($cmsRoot . '/library')
		){
			return true;
		}
		return false;
	}

	public static function removeCMSDirs($name): bool{
		$cmsRoot = $_SERVER['DOCUMENT_ROOT']. '/public/uploads/cms/'. $name;
		if (rmdir($cmsRoot) &&
			rmdir($cmsRoot . '/dishes') &&
			rmdir($cmsRoot . '/library')
		){
			return true;
		}
		return false;
	}

	public static function renameCMSDir($name): bool{
		$oldDir = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/cms/'. $name;
		$date = new \DateTime();
		$newDir = $oldDir . '-deleted-' . $date->format("Ymd_Hisu");
		if (rename($oldDir, $newDir))
		{
			return true;
		}
		return false;
	}

	public static function createUserDirs($user){
		mkdir($_SERVER['DOCUMENT_ROOT']. '/public/uploads/users/' . $user);
	}

	public static function uploadImage($file, $name, $dir){
		try{
			if(!isset($file)){ 	
				return false;
			}
			
			$target_dir = $_SERVER['DOCUMENT_ROOT'] .'/public/' . $dir;
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
			ErrorReporter::report($e->getMessage());
			return false;
		}
		
		$addingFile = move_uploaded_file($file["tmp_name"], $target_file);
		$publicDir = str_replace('/var/www/html/public/', '', $target_file);
		return $addingFile ? $publicDir : false;
	}


}