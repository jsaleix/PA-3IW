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
			if(gettype($site) === '\App\Models\Site')
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

	public static function renderCMSLink($path, $site)
	{
		echo 'test';
		$url = DOMAIN . '/site';
		if(gettype($site) == 'array' )
		{
			$url .= $site['subDomain'] . '/' . $path;
		}
		if(gettype($site) === '\App\Models\Site')
		{
			$url .= $site->getSubDomain() . '/' . $path;
		}
		return $url;
	}

}