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
}