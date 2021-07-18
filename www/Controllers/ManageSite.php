<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;

use App\Models\Site;

class ManageSite{

	public function createAction(){
		$user = Security::getUser();
		if(!$user)
        {
            header('Status: 301 Moved Permanently', false, 301);      
            header('Location: /?error=not_connected'); 
            exit();
        }
        $step = $_GET['step']??1;

        switch($step)
        {
            case '2':
                $view = new View("/onBoarding/step2", 'onBoarding' );
                break;

            case '3':
                $view = new View("/onBoarding/step3", 'onBoarding' );
                break;

            case 'finalize':

                [ 'name' => $name, 'description' => $description, 
                  'type' => $type, 'category' => $category, 'subDomain' => $subDomain ] = $_POST;

                if(!$name || !$description || !$type || !$category || !$subDomain){
                    $return=array("status" => "Missing field(s)", "code" => 400);
                    self::returnJson($return['status'], $return['code']);
                    exit;
                }
                $prefix = bin2hex(random_bytes(4));
                $site = new Site();
                $site->setSubDomain($subDomain);
                $result = $site->findOne();
                if( $result )
                    self::returnJson("subDomain", 460);
                $site->setSubDomain(null);
                $site->setName($name);
                $result = $site->findOne();
                if( $result )
                    self::returnJson("name", 461);
                $site->setName(null);
                do{
                    $prefix = bin2hex(random_bytes(4));
                    $site->setPrefix($prefix);
                    $result = $site->findOne();
                } while( $result );
                $site->setName($name);
                $site->setDescription($description);
                $site->setCreator($user);
                $site->setSubDomain($subDomain);
                $site->setType($type);
                $creation = $site->initializeSite();
                if(!$creation){
                    $return=array("status" => "Unsuccessful", "code" => 500);
                }else{
                    $return=array("status" => "Successfully created", "code" => 201);
                }
                self::returnJson($return['status'], $return['code']);
                exit;
               
            case 'test':
                $site = new Site();
                $site->setSubDomain('si');
                $result = $site->findOne();
                if( $result )
                    self::returnJson("subDomain", 460);
                $site->setSubDomain(null);
                $site->setName('fado');
                $result = $site->findOne();
                if( $result )
                    self::returnJson("name", 461);
                $site->setName(null);
                do{
                    $prefix = bin2hex(random_bytes(4));
                    $site->setPrefix($prefix);
                    $result = $site->findOne();
                } while( $result );
                $site->setName('menu');
                $site->setDescription('description');
                $site->setImage('rien');
                $site->setCreator($user);
                $site->setSubDomain('menu');
                $site->setPrefix($prefix);
                $site->setType('type default');
                $creation = $site->initializeSite();
                !$creation ? self::returnJson("Unsuccessful", 500) : self::returnJson("Successfully created", 201);
                exit;

            default:
            $view = new View("/onBoarding/step1", 'onBoarding' );

        }
	}

    private function returnJson(string $status, int $code){
        //header($status, false, $code); 
        http_response_code($code);
        echo json_encode(array('status' => $status, 'code' => $code));
        exit;
    }

}