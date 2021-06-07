<?php

namespace App\Core;

use App\Core\Token;
use App\Models\User;
class Security
{

	public function isConnected(){
		if( ($uid = Token::verifyToken()) != 0)
			return $uid;
		return 0;
	}

	public function connect($userPDO){
		$token = new Token();
		if( $uid = $token->createToken($userPDO) != null)
			return $uid;
		return 0;
	}
}