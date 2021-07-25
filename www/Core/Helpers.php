<?php

namespace App\Core;

class Helpers
{

	public static function clearLastname($lastname){
		return mb_strtoupper(trim($lastname));
	}

	public static function customRedirect($url, $site = null){
		$newUrl = 'Location: '. DOMAIN;
		if((strpos('/', $url) == 0))
		{
			$url = substr($url, 1);	
		}
		if($site){
			if(gettype($site) == 'array' )
			{
				$site = $site['subDomain'];
				$newUrl .= '/site/' . $site . '/' . $url;
			}
			if(gettype($site) === 'object')
			{
				$site = $site->getSubDomain();
				$newUrl .= '/site/' . $site . '/' . $url;
			}
		}else{
			$newUrl .= '/' . $url;
		}
		header($newUrl);
		exit();
	}

	public static function errorStatus(){
		http_response_code(404);
		exit();
	}

	public static function serverErrorStatus(){
		http_response_code(500);
		exit();
	}

	public static function renderCMSLink($path, $site)
	{
		$url = DOMAIN . '/site';
		if(gettype($site) == 'array' )
		{
			$url .= '/' . $site['subDomain'] . '/' . $path;
		}
		if(gettype($site) === 'object')
		{
			$url .= '/' . $site->getSubDomain() . '/' . $path;
		}
		return $url;
	}

	public static function displayAlert($type, $message, $time){

		return '<script>displayAlert("'.$type.'", "'.$message.'", '.$time.');</script>';
	}

	public function sanitizeList($string){
		return trim(preg_replace('/\s\s+/', ' ', nl2br($string) ));
	}
}