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
        if( !$bookingSettingsObj->findOne(TRUE)){
            $this->setupSettings($site, $bookingSettingsObj);
            return;
        }
        if( !$bookingSettingsObj->getIsSetUp()){
            $this->setupPlanning($site, $bookingSettingsObj);
            return;
        }

        $this->setupCalendar($site, $bookingSettingsObj);
        return;
        
    }

    public function addBookingSettingAction($site){
        $bookingSettingsObj = new Booking_settings($site['prefix']);
    }

    public function editSettingsAction($site){
        $bookingSettingsObj = new Booking_settings($site['prefix']);
        $formEdit = $bookingSettingsObj->form();
        $view = new View('booking', 'back', $site);
        $view->assign('pageTitle', "Manage the events");
        $view->assign("form", $formEdit);
        if( !empty($_POST)){
            $errors = FormValidator::check($formEdit, $_POST);
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $pdoResult = $bookingSettingsObj->edit($_POST);
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
        $form= $bookingSettingsObj->form();
        $view = new View('booking', 'back', $site);
        $view->assign("form", $form);
        $view->assign('pageTitle', "Set up settings");
        $view->assign("settings", TRUE);
        if( !empty($_POST)){
            $errors = FormValidator::check($form, $_POST);
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $_POST['enabled'] = 1;
            $pdoResult = $bookingSettingsObj->populate($_POST, TRUE);
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
        $planObj = new Planning($site['prefix']);
        $plans = $planObj->findAll();
        $forms = [];
        foreach($plans as $p){
            $plan = new Planning($site['prefix']);
            $plan->populate($p);
            $forms[] = $plan;
        }
        $fieldNumber = count($plans);

        $view = new View('booking', 'back', $site);
        $view->assign('pageTitle', "Set up planning");
        $view->assign("planning", TRUE);
        $form = $planObj->form($forms);
        $view->assign("f", $form);
        if( !empty($_POST)){
            $errors = FormValidator::check($form, $_POST);
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $savedPlans = [];
            $totalPost = count($_POST);
            $currArray = [];
            $index = 1;
            for($i=1; $i<=$totalPost; $i++){
                if( $i % ( $totalPost/$fieldNumber ) == 0 ){
                    $planObj = new Planning($site['prefix']);
                    $currArray = array_slice($_POST, $i - ($totalPost/$fieldNumber) , ($totalPost/$fieldNumber));
                    $this->sanatizeAssociativeKeys($currArray, "-".$index);
                    $currArray[ "id" ] = $index ;
                    $pdoResult = $planObj->edit($currArray);
                    if( !$pdoResult ){
                        echo "aha";
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