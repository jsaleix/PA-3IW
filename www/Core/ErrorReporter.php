<?php

namespace App\Core;
use App\Core\Security;

class ErrorReporter
{

	public static function report(String $content): void{
		$file = $_SERVER['DOCUMENT_ROOT'] . '/reports/report.txt';
		if ( !file_exists( $file ) ){
            if(!touch($file)) 
                return;
        }

        $date = new \DateTime();
        $preContent =  $date->format("d-m-y h:i:s") ;
        if($user = Security::getUser()){
            $preContent .= '(by user ' . $user . ')';
        }
        $content = $preContent . ':' . $content . PHP_EOL;

        file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
	}


}