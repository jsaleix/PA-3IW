<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Security;
use App\Core\FormValidator;
use App\Core\FileUploader;
use App\Core\ErrorReporter;

use App\Models\Site;

use CMS\Models\Page;
use CMS\Models\Post;
use CMS\Models\Dish_Category;

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
                return $this->createSite();
                exit;

            default:
            $view = new View("/onBoarding/step1", 'onBoarding' );

        }
	}

    private function createSite(){
        if(!empty($_POST)){
            try{
                $errors = [];
                $site   = new Site();
                $form   = $site->formCreate();
                $errors = FormValidator::check($form, $_POST);
				if( count($errors) > 0)
				{
                    ErrorReporter::report("Invalid form: " . implode(' - ', $errors));
					throw new \Exception('Invalid form');
				}
                
                /*
                * Checkings
                */
                #Checks if sub domain is not already taken
                $tmpSite = new Site();

                if ( !preg_match('#^[0-9]*[a-zA-Z]+[a-zA-Z0-9]*$#', $_POST['subDomain']) ) {
                    throw new \Exception('Invalid chars in sub domain');
                }

                if (in_array($_POST['subDomain'], $site->getInvalidDomains())) {
                    throw new \Exception('Invalid sub Domain');
                }

                $tmpSite->setSubDomain($_POST['subDomain']);
                $result = $tmpSite->findOne();
                if( $result ){
                    //self::returnJson("subDomain", 460);
                    throw new \Exception('Sub domain already taken');
                }

                $tmpSite->setSubDomain(null);
                $tmpSite->setName($_POST['name']);
                $result = $tmpSite->findOne();
                if( $result ){
                    //self::returnJson("name", 461);
                    throw new \Exception('Name already taken');
                }

                $tmpSite->setName(null);

                #Checking if db prefix is not already taken
                #If true, generate a new one until it's not taken
                do{
                    $prefix = bin2hex(random_bytes(4));
                    $tmpSite->setPrefix($prefix);
                    $result = $tmpSite->findOne();
                } while( $result );

                #If arrived until here, all is supposed to be good
                $site->populate($_POST);
                $site->setPrefix($prefix);
                $site->setCreator(Security::getUser());

                $creation = $this->initializeSite($site);
                if(!$creation){
                    self::returnJson( "Unsuccessful" , 400 );
                }else{
                    self::returnJson( "Successfully created" , 201 );
                }

            }catch(\Exception $e){
                ErrorReporter::report($e->getMessage());
                self::returnJson($e->getMessage(), 400);
            }
        }else{
            self::returnJson('empty body', 400);
        }
    }

    public function initializeSite($site){
        if( !$site->getName() ){ return false; }
        if( !$site->getSubDomain() ){ return false; }
        if( $site->getId() ){ return false; }
        if( !($site->save()) ){ return false; }

        // Creation of new tables 
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/Assets/scripts';
        clearstatcache();
        $sqlFiles = array(
            'dish_category', 'dish', 'booking','booking_settings', 'booking_planning', 
            'booking_planning_data','page', 'medium', 'post', 'content', 'comment', 
            'menu', 'menu_dish_association', 'post_medium_association'
        );


        try{
            foreach($sqlFiles as $file){
                if(!file_exists($dir . '/' . $file .'.script' )){
                    throw new \Exception("Missing required file " . $file);
                }
            }

            $toReplace = [':X', ':prefix'];
            $replaceBy = [$site->getPrefix(), DBPREFIXE];


            foreach( $sqlFiles as $table){
                $table  = file_get_contents($dir . '/'.$table.'.script');
                $script = str_replace($toReplace, $replaceBy, $table);
                $create = $site->createTable($script);
                if(!$create){ 
                    throw new \Exception("Not able to create table:" . $table);
                }
            }

            #Preparing the the new site
            $page = new Page($site->getPrefix());
            $page->setName('home');
            $page->setCreator(Security::getUser());
            $page->setMain(1);
            if( !$page->save() ){
                throw new \Exception("Not able to create page at site init");
            }

            if(!FileUploader::createCMSDirs($site->getSubDomain())){
                throw new \Exception("Not able to create dirs at site init");
            }

            $post = new Post($site->getPrefix());
            $post->setTitle('Welcome');
            $post->setContent('This is your first article on your new website.');
            $post->setPublisher(Security::getUser());
            if( !$post->save() ){
                throw new \Exception('post');
            }
            
            $dishCatObj = new Dish_Category($site->getPrefix());
            $dishCatArr = [ 'Starters', 'Dishes', 'Desserts', 'Drinks'];
            foreach($dishCatArr as $cat){
                $dishCatObj->setName($cat);
                $dishCatObj->save();
            }

            return true;
        }catch(\Exception $e){
            ErrorReporter::report($e->getMessage());
            FileUploader::removeCMSDirs($site->getSubDomain());
            $site->delete();
            return false;
        }
        return false;
    }

    private function returnJson(string $status, int $code){
        //header($status, false, $code); 
        http_response_code($code);
        echo json_encode(array('status' => $status, 'code' => $code));
        exit;
    }

}