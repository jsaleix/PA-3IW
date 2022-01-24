<?php

namespace App\Middlewares;


use App\Core\Security;

class Middleware{
    
    public function auth(){
        if( !Security::isConnected()){
            header('Location: '.DOMAIN . '/login');
            exit();
        }
    }

    public function noAuth(){
        if( Security::isConnected()){
            header('Location: '.DOMAIN);
            exit();
        }
    }

    public function isAdmin(){
        try{
            self::auth();
            $role = Security::getRole();
            if(!$role) throw new \Exception('No role found');
            if($role->getIsAdmin() != 1) throw new \Exception('Not allowed');
        }catch(\Exception $e){
            echo $e->getMessage();
            header('Location: '.DOMAIN . '?notAllowed');
            exit();
        }
    }

}