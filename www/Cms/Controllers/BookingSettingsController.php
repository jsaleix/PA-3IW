<?php

namespace CMS\Controller;

use App\Core\FormValidator;

use CMS\Core\CMSView as View;

use CMS\Models\Booking;
use CMS\Models\Booking_settings;
use CMS\Models\Booking_planning as Planning;

class BookingSettingsController{
    
    public function manageBookingSettingsAction($site){
        $bookingSettingsObj = new Booking_settings($site['prefix']);
        if( !$bookingSettingsObj->findOne(TRUE)){//IF THERE IS NO SETTINGS MAKE THE USER SET THEM
            $this->setupSettings($site, $bookingSettingsObj);
            return;
        }
        if( !$bookingSettingsObj->getIsSetUp()){//IF THERE IS SETTINGS BUT NO PLANING, MAKE THE USER SET HIS PLANNING
            $this->setupPlanning($site, $bookingSettingsObj);
            return;
        }

        $this->setupCalendar($site, $bookingSettingsObj);//IF EVERY THING IS CHECKED, SHOW THE RESERVATIONS ACTIVES AND WAITING
        return;
        
    }

    public function addBookingSettingAction($site){
        $bookingSettingsObj = new Booking_settings($site['prefix']);
    }

    public function editSettingsAction($site){
        $bookingSettingsObj = new Booking_settings($site['prefix']);//CREATE VIEW AND FORM TO EDIT THE BOOKING SETTINGS
        $formEdit = $bookingSettingsObj->form();
        $view = new View('booking', 'back', $site);
        $view->assign('pageTitle', "Manage the events");
        $view->assign("form", $formEdit);
        if( !empty($_POST)){
            $errors = FormValidator::check($formEdit, $_POST);//CHECK AND SANATIZE THE FORM
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $pdoResult = $bookingSettingsObj->edit($_POST);//IF EVERY THING IS OK, SAVE THE SETTINGS
            if( $pdoResult ){
                $message = "Settings modified !";
                $view->assign("message", $message);
                \App\Core\Helpers::customRedirect('/admin/booking/settings', $site);
            } else {
                $errors[] = "Cannot set these settings";
                $view->assign("errors", $errors);
            }
        }
    }

    private function setupSettings($site, $bookingSettingsObj){
        $form= $bookingSettingsObj->form();//CREATE VIEW AND FORM TO CREATE THE BOOKING SETTINGS
        $view = new View('booking', 'back', $site);
        $view->assign("form", $form);
        $view->assign('pageTitle', "Set up settings");
        $view->assign("settings", TRUE);
        if( !empty($_POST)){
            $errors = FormValidator::check($form, $_POST);//CHECK AND SANATIZE THE FORM
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $_POST['enabled'] = 1;//CHANGE THE BOOKING SETTINGS TO CREATED
            $pdoResult = $bookingSettingsObj->populate($_POST, TRUE);//CREATE THE SETTINGS ON DB
            if( $pdoResult ){
                $message = "Settings added !";
                $view->assign("message", $message);
                \App\Core\Helpers::customRedirect('/admin/booking', $site);
            } else {
                $errors[] = "Cannot set these settings";
                $view->assign("errors", $errors);
            }
        }
    }

    private function setupPlanning($site, $bookingSettingsObj){
        $planObj = new Planning($site['prefix']);//CREATE A PLANNING OBJ AND GET ALL DAYS FROM THE WEEK FROM DB
        $plans = $planObj->findAll();
        $forms = [];
        foreach($plans as $p){//STOCK THE DAYS ON AN ARRAY TO RENDER THE FORM FOR EVERY DAY IN VIEW
            $plan = new Planning($site['prefix']);
            $plan->populate($p);
            $forms[] = $plan;
        }
        $fieldNumber = count($plans);

        $view = new View('booking', 'back', $site);//CREATE THE VIEW AND FORM TO MODIFY EVERY DAYS'S PLANNING
        $view->assign('pageTitle', "Set up planning");
        $view->assign("planning", TRUE);
        $form = $planObj->form($forms);
        $view->assign("f", $form);
        if( !empty($_POST)){
            $errors = FormValidator::check($form, $_POST);//CHECK AND SANATIZE THE FORM
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $savedPlans = [];//CREATE AN ARRAY TO STORE THE MODIFIED PLANNINGS
            $totalPost = count($_POST);//GET ALL INPUTS THAT CAN BE MODIFIED 
            $index = 1;//INDEX THAT WILL BE INCREMENTED DEPENDING THE NUMBER OF INPUTS PER OBJECT, STARTS ON ONE FOR DB'S IDS
            for($i=1; $i<=$totalPost; $i++){//LOOP ON ALL THE MODIFIABLE INPUTS
                if( $i % ( $totalPost/$fieldNumber ) == 0 ){//EVERY 4 INPUTS(BECAUSE WE HAVE 4 FIELDS MODIFIABLE BY OBJECT)
                    $planObj = new Planning($site['prefix']);//WE CREATE A NEW PLAN OBJ
                    $savedPlans = array_slice($_POST, $i - ($totalPost/$fieldNumber) , ($totalPost/$fieldNumber));//AND SAVE THE 4 INPUTS IN OUR ARRAY
                    $this->sanatizeAssociativeKeys($savedPlans, "-".$index);//SANATIZE THE DATA OF OUR ARRAY
                    $savedPlans[ "id" ] = $index ;//SET ARRAY ID TO USE THE EDIT DB FUNCTION
                    $pdoResult = $planObj->edit($savedPlans);//TRY TO EDIT THE DAY CURRENTLY ASSOCIATED
                    if( !$pdoResult ){
                        $errors = "Booking planning not modified!";
                        $view->assign("errors", $errors);
                        break;
                    }
                    $index++;
                }
            }
            $bsObj = new Booking_settings($site['prefix']);
            $bsObj->setId($bookingSettingsObj->getId());
            $bsObj->setIsSetUp(1);
            $bsObj->save();
            $message = "Booking planning created !";
            $view->assign("message", $message);
            \App\Core\Helpers::customRedirect('/admin/booking', $site);
            return;
        }
    }

    private function sanatizeAssociativeKeys(&$array, $sanatizer){
        foreach($array as $key => $value){
            $newKey = str_replace($sanatizer, "", $key);
            $array[$newKey] = $array[$key];
            unset($array[$key]);
        }
    }

    private function setupCalendar($site, $bookingSettingsObj){
        //print_r($bookingSettingsObj);
        $bookingObj = new Booking($site['prefix']);
        $bookingObj->setStatus("IS FALSE");
        $booking = $bookingObj->findAll();

        $fields = [ 'client', 'date', 'number', 'accept', 'delete' ];
		$datas = [];

        if($booking){
            foreach($booking as $item){
                $accept = \App\Core\Helpers::renderCMSLink( "admin/booking/accept?id=".$item['id'], $site);
                $decline = \App\Core\Helpers::renderCMSLink( "admin/booking/decline?id=".$item['id'], $site);
                $buttonAccept = '<a href="' . $accept . '">Go</a>';
                $buttonDelete = '<a href="' . $decline . '">Go</a>';
                $formalized = "'" . $item['client'] . "','" . $item['date'] . "','" . $item['number'] .  "','" . $buttonAccept . "','" . $buttonDelete . "'";
				$datas[] = $formalized;
            }
        }
        $view = new View('list', 'back', $site);
		$view->assign("fields", $fields);
		$view->assign("datas", $datas);
		$view->assign('pageTitle', "Manage the comments");
    }

    public function acceptBookingAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'Booking not found ';
			exit();
		}
        $bookingObj = new Booking($site['prefix']);
        $bookingObj->setId($_GET['id']);
        if( $bookingObj->findOne(TRUE)){
            $bookingObj->setStatus(1);
            $bookingObj->save();
        }
        print_r($bookingObj);
    }

    public function deleteBookingAction($site){
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'Booking not found ';
			exit();
		}
        $bookingObj = new Booking($site['prefix']);
        $bookingObj->setId($_GET['id']);
        if( $bookingObj->findOne(TRUE)){
            echo "Should delete";
            //$bookingObj->delete();
        }
        print_r($bookingObj);
    }
}