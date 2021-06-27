<?php

namespace App\Core;

use App\Models\User;

class Token{
    
	public function createToken($userPDO){
		try{
			$token = bin2hex(random_bytes(128));
			$user = new User();
			$user->setId($userPDO['id']);
			$user->setToken($token);
			$user->save();
			if ( session_status() === PHP_SESSION_NONE )
				session_start();
			$_SESSION['token'] = $token;
			return $user->getId();
		} catch( Exception $e){
			return 0;
		}
	}
	
	public function verifyToken(){
		try{
			if ( session_status() === PHP_SESSION_NONE )
				return 0;
			$user = new User();
			if(!isset($_SESSION['token'])) return 0;
			$user->setToken($_SESSION['token']);
			$result = $user->findOne();
			if( !$result)
				return 0;
			return Token::createToken($result);
		} catch( Exception $e){
			return 0;
		}
	}

	public function destroyToken($uid){
		try{
			$user = new User();
			$user->setId($uid);
			$user->setToken("IS NULL");
			$user->save();
			unset($_SESSION['token']);
			return 1;
		} catch ( Exception $e){
			return 0;
		}
	}
}