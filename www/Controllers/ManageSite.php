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
                }else{
                    $site = new Site();
                    $site->setName($name);
                    $site->setDescription($description);
                    $site->setImage('rien');
                    $site->setCreator(1);
                    $site->setSubDomain($subDomain);
                    $site->setPrefix('imp');
                    $site->setType($type);
                    $site->save();
                    $site->initializeSite();
                    $return=array("status" => "Successfully created", "code" => 201);
                    self::returnJson($return['status'], $return['code']);
                    exit;
                }
                
            case 'test':
                $site = new Site();
                $site->setName('test');
                $site->setDescription('description');
                $site->setImage('rien');
                $site->setCreator(1);
                $site->setSubDomain('impera');
                $site->setPrefix('imp');
                $site->setType('type default');
                $site->initializeSite();
                break;

            default:
            $view = new View("/onBoarding/step1", 'onBoarding' );

        }
	}

    private function returnJson(string $status, int $code){
        header($status, false, $code); 
        json_encode(array('status' => $status, 'code' => $code));
        exit;
    }

    private function createAWebSite(){

    }

}