<?php

namespace CMS\Controller;

use App\Core\FormValidator;

use CMS\Core\CMSView as View;

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
        

        /*if( $bookingSettingsObj->findOne(TRUE)){
            //MODIFY
            $formEdit = $bookingSettingsObj->formEdit();
            $view = new View('editEvent', 'back', $site);
		    $view->assign('pageTitle', "Manage the events");
            $view->assign("form", $formEdit);
            $step = "settings";
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
                    \App\Core\Helpers::customRedirect('/admin/events', $site);
                } else {
                    $errors[] = "Cannot set these settings";
                    $view->assign("errors", $errors);
                }
            }
        } else {
            //ADD
            $formAdd = $bookingSettingsObj->formAdd();
            $view = new View('create', 'back', $site);
            $view->assign("form", $formAdd);
		    $view->assign('pageTitle', "Manage the events");
            $step = "create";
            if( !empty($_POST)){
                $errors = FormValidator::check($formAdd, $_POST);
                if( count($errors) > 0){
                    $view->assign("errors", $errors);
                    return;
                }
                $_POST['enabled'] = 1;
                $pdoResult = $bookingSettingsObj->populate($_POST, TRUE);
                if( $pdoResult ){
                    $message = "Settings added !";
                    $view->assign("message", $message);
                    \App\Core\Helpers::customRedirect('/admin/events', $site);
                } else {
                    $errors[] = "Cannot set these settings";
                    $view->assign("errors", $errors);
                }
            }
        }
        
        $view->assign('step', $step);*/
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

        $view = new View('booking', 'back', $site);
        $view->assign('pageTitle', "Set up planning");
        $view->assign("planning", TRUE);
        $form = $planObj->form($forms);
        $view->assign("f", $form);
        if( !empty($_POST)){
            echo "ahahaha";
            $errors = FormValidator::check($form, $_POST);
            var_dump($_POST["available-1"]);
            echo "data : ".count($_POST)." form : ".count($form["inputs"]);
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            var_dump($_POST);
            /*$pdoResult = $planObj->edit($_POST);
            if( $pdoResult ){
                $message = "Booking planning !";
                $view->assign("message", $message);
                \App\Core\Helpers::customRedirect('/admin/booking', $site);
            } else {
                $errors[] = "Booking planning not modified";
                $view->assign("errors", $errors);
            }*/
        }
    }

    private function setupCalendar($site, $bookingSettingsObj){
        echo "AQUIIIIIII";
    }
}