<?php

namespace App\Core;

use App\Core\Token;
use App\Models\User;
class Security
{

	public static function isConnected(){
		if( ($uid = Token::verifyToken()) != 0)
			return $uid;
		return 0;
	}

	public static function connect($userPDO){
		$token = new Token();
		if( $uid = $token->createToken($userPDO) != null)
			return $uid;
		return 0;
	}

	public static function disconnect(){
		$uid = Security::isConnected();
		return $uid == 0 ? 0 : Token::destroyToken($uid);
	}

	public static function getUser(){
		if ( session_status() === PHP_SESSION_NONE || !isset($_SESSION['token']) )
			return 0;
		$user = new User();
		$user->setToken($_SESSION['token']);
		$result = $user->findOne();
		return $result['id'];
	}
}