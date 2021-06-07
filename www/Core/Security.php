<?php

namespace App\Core;

use App\Core\Token;
use App\Models\User;
class Security
{

	public function isConnected(){
		$token = new Token();
		if( ($uid = $token->verifyToken()) != 0)
			return $uid;
		return 0;
	}

	public function connect($userPDO){
		$token = new Token();
		if( $uid = $token->createToken($userPDO) != null)
			return $uid;
		return 0;
	}

	public function getCurrentUser(){
		if ( session_status() === PHP_SESSION_NONE )
			return false;
		$user = new User();
		$user->setToken($_SESSION['token']);
		$result = $user->findOne();
		return $result ? $result['id'] : null;
	}
}