<?php

namespace App\Controller;

use App\Core\View;

use App\Models\Site;

class ManageSite{

	public function createAction(){
        $connected = $_SESSION['connected']??false;
		if(false && !$connected)
        {
            header('Status: 301 Moved Permanently', false, 301);      
            header('Location: /?error=not_connected'); 
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
                $prefix = random_bytes(4);
                $prefix = bin2hex($prefix);
                $site = new Site();
                $site->setName($name);
                $site->setDescription($description);
                $site->setCreator(1);
                $site->setSubDomain($subDomain);
                $site->setPrefix($prefix);
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
                $prefix = random_bytes(4);
                $prefix = bin2hex($prefix);
                $site = new Site();
                $site->setName('test');
                $site->setDescription('description');
                $site->setImage('rien');
                $site->setCreator(1);
                $site->setSubDomain('impera');
                $site->setPrefix($prefix);
                $site->setType('type default');
                $creation = $site->initializeSite();
                if(!$creation){
                    $return=array("status" => "Unsuccessful", "code" => 500);
                }else{
                    $return=array("status" => "Successfully created", "code" => 201);
                }
                self::returnJson($return['status'], $return['code']);
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