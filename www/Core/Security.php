<?php

namespace App\Core;

use App\Core\Token;
use App\Models\User;
use App\Models\Role;

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

	public function getRole(){
		try{
			$userId = self::getUser();
			if (!$userId) throw new \Exception('No user defined'); 
	
			$usrObj = new User();
			$usrObj->setId($userId);
			$check = $usrObj->findOne(TRUE);
			if(!$check) throw new \Exception('User not found'); 
			if(!$usrObj->getRole()) throw new \Exception('User has no role'); 

			$roleObj = new Role();
			$roleObj->setId($usrObj->getRole());
			$exists = $roleObj->findOne(TRUE);

			if(!$exists) throw new \Exception('Role not found'); 

		}catch(\Exception $e){
			//echo $e->getMessage();
			return false;
		}
		return $roleObj;
	}

	public function isAdmin(){
		if(!self::getUser()) return false;
		$role = self::getRole();

		if(!$role) return false;
		$roleObj = new Role();
		$roleObj->setId($role->getId());
		$roleObj->setIsAdmin(1);
		$exists = $roleObj->findOne();

		if(!$exists) return false;
		return true;
	}
}