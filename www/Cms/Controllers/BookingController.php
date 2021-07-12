<?php

namespace CMS\Controller;

use CMS\Models\Booking;
use CMS\Models\Booking_settings;
use CMS\Core\CMSView as View;


use App\Core\FormValidator;
use App\Core\Security;

class BookingController{

    public function manageBookingsAction($site){
        $bookingObj = new Booking($site['prefix']);
    }

    public function addBookingAction($site){
        $bookingObj = new Booking($site->getPrefix());
        $bookingSettingsObj = new Booking_settings($site->getPrefix());
        if( !$bookingSettingsObj->findOne(TRUE))
            return;
        $form = $bookingObj->form($bookingSettingsObj);
        $view = new View('booking', 'front', $site);
		$view->assign("form", $form);
		$view->assign("pageTitle", "Add a reservation");
/*
        // FIRST INPUT["NUMBER"] -> Possible ? 
        // INPUT["DATE"] -> min TODAY - max 1 MONTH WHERE planning->Disabled = 1 
        // INPUT["TIME"] -> Foreach Start + 30 until End WHERE reserver + number <= totalNumberPerReservation
        if(!empty($_POST)){
            $errors = FormValidator::check($form, $_POST);
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $bookingObj->setDate($_POST['date']." ".$_POST['time']);
            $reserved = $bookingObj->findAll();
            $bookingObj->setClient(Security::getUser());
            $booking = $bookingObj->findOne();
            if( $booking ){
                if($booking['status'] == 1)
                    $errors[] = "Vous avez déjà une reservation active à cette date";
                else
                    $errors[] = "Vous avez déjà une reservation en attente à cette date";
                $view->assign("errors", $errors);
                return;
            }
            $numberReserved = 0;
            if( !empty($reserved) && count($reserved) > 0 ){
                foreach($reserved as $book){
                    $numberReserved += $book["number"];
                }
            }
            if($numberReserved + $_POST['number'] > $bookingSettingsObj->getTotalNumberPerReservation()){
                $errors[] = "Pas assez de table disponible pour votre réservation";
                $view->assign("errors", $errors);
                return;
            }
            $bookingObj->setNumber($_POST['number']);
            $pdoResult = $bookingObj->save();
            if( $pdoResult ){
                $message = "Reservation en attente de confirmation !";
                $view->assign("message", $message);
            } else {
                $errors[] = "Une erreur est survenue lors de la création de la réservation";
                $view->assign("errors", $errors);
            }
        }*/
    }

    public function apiCheckPersonNumber($site){
        return array(bool(true));
    }

    public function apiGetCalendar($site){
        return array();
    }

    public function apiGetTimes($site){
        return array();
    }
}