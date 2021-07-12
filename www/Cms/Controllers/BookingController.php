<?php

namespace CMS\Controller;

use CMS\Models\Booking;
use CMS\Models\Booking_planning;
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
        $bookingPlanningObj = new Booking_planning($site->getPrefix());
        if( !$bookingSettingsObj->findOne(TRUE))
        {
            $view = new View('featureNotAvailable', 'front', $site);
            return;
        }
        $form = $bookingObj->form($bookingSettingsObj); //CREATE FORM AND VIEW
        $view = new View('booking', 'front', $site);
		$view->assign("form", $form);
		$view->assign("pageTitle", "Add a reservation");
        if(!empty($_POST)){
            if( !Security::getUser()){ //CHECK LOGIN
                $errors[] = "You must be logged in to reserve";
                $view->assign("errors", $errors);
                return;
            }

            if( $bookingSettingsObj->getEnabled() == 0 || $bookingSettingsObj->getIsSetUp() == 0 ){ //CHECK IF THE RESTAURANT ACCEPT RESERVATIONS IN CASE SOMEONES TRIES TO OVERPASS SECURITY
                $errors[] = "Unfortunately, reservations aren't active on this retaurant";
                $view->assign("errors", $errors);
                return;
            }
            
            $errors = FormValidator::check($form, $_POST); //CHECK AND SANATIZE FORM
            if( count($errors) > 0){
                $view->assign("errors", $errors);
                return;
            }
            $day = new \DateTime($_POST["date"]);
            $bookingPlanningObj->setId($day->format('N'));
            $bookingPlanningObj->findOne(TRUE);
            if( $bookingPlanningObj->getDisabled() == 0){//CHECK IF RESTAURANT IS WORKING
                $errors[] = "We don't work this day, try another one";
                $view->assign("errors", $errors);
                return;
            } 
            $bookingObj->setDate($_POST['date']." ".$_POST['time']);
            $reserved = $bookingObj->findAll();
            $bookingObj->setClient(Security::getUser());
            $booking = $bookingObj->findOne();
            if( $booking ){ // CHECK IF PEOPLE DONT TRY TO RESERVE MORE THAN ONE TIME PER SCHEDULE
                if($booking['status'] == 1)
                    $errors[] = "Vous avez déjà une reservation active à cette date";
                else
                    $errors[] = "Vous avez déjà une reservation en attente à cette date";
                $view->assign("errors", $errors);
                return;
            }
            $numberReserved = 0;
            if( !empty($reserved) && count($reserved) > 0 ){//GET THE NUMBER OF PEOPLE THAT RESERVED FOR THIS SCHEDULE
                foreach($reserved as $book){
                    $numberReserved += $book["number"];
                }
            }
            if($numberReserved + $_POST['number'] > $bookingSettingsObj->getTotalNumberPerReservation()){//CHECK IF THE NUMBER ALREADY RESERVED + THE ONE THAT IS TRYING TO RESERVE DOESNT EXCEED THE NUMBER ALLOWED BY THE RESTAURANT
                $errors[] = "Pas assez de table disponible pour votre réservation";
                $view->assign("errors", $errors);
                return;
            }
            $bookingObj->setNumber($_POST['number']);
            $pdoResult = $bookingObj->save(); //EVERY THING IS OK, SAVE IN DATABASE
            if( $pdoResult ){
                $message = "Reservation en attente de confirmation !";
                $view->assign("message", $message);
            } else {
                $errors[] = "Une erreur est survenue lors de la création de la réservation";
                $view->assign("errors", $errors);
            }
        }
    }

    public function apiCheckPersonNumberAction($site){
        $errors = [];
        if( !empty($_GET['number'])){
            $bookingSettingsObj = new Booking_settings($site->getPrefix());
            $bookingSettingsObj->findOne(TRUE);
            $message;
            if( $bookingSettingsObj->getEnabled() == 0 || $bookingSettingsObj->getIsSetUp() == 0 ){ //CHECK IF THE RESTAURANT ACCEPT RESERVATIONS IN CASE SOMEONES TRIES TO OVERPASS SECURITY
                $code = 422;
                $errors [] = "Booking is not enabled on this site";
            }
            $number = \App\Core\FormValidator::sanitizeData($_GET['number']);
            if($bookingSettingsObj->getMaxNumberPerReservation() < $number){//CHECK THAT THEY DONT TRY TO RESERVE FOR MORE PERSONS THAN ALLOWED
                $code = 422;
                $errors [] = "You are trying to reserve for more persons than the restaurant enables. Please follow the instructions on the inputs.";
            }
            if( empty($errors) ){
                $code = 200;
            }
        } else {
            $code = 400;
            $errors [] = "Bad request";
        }
        http_response_code($code);
        echo json_encode(array('code' => $code, 'errors' => ($errors)));
    }

    public function apiGetCalendarAction($site){
        $today = new \DateTime();
        $maxDate = new \DateTime();
        $maxDate->add(new \DateInterval("P2M1D"));//ADDING 2 MONTH AND 1 DAY TO GET LAST DAY ON DATEPERIOD
        $period = new \DatePeriod($today, \DateInterval::createFromDateString('1 day'), $maxDate);//CREATING A PERIOD TO LOOP ON IN FUTURE BETWEEN TODAY AND MAX DATE

        $bookingPlanningObj = new Booking_planning($site->getPrefix());
        $plannings = $bookingPlanningObj->findAll();
        
        $bookingDates = [];
        $acceptedDates = [];

        foreach( $plannings as $plan){//LOOPING ON PLANNINGS TO CREATE AN ARRAY WITH ALL WANTED DAYS THAT WORKS WITH "N" FORMAT FROM DATETIME
            if($plan['disabled'] == 1){
                array_push($acceptedDates, $plan['id']);
            }
        }
        foreach($period as $p){//LOOPING ON THE PERIOD TO POPULATE THE ARRAY WITH ALL WORKING DAYS FOR 2 MONTH
            if( in_array($p->format('N'), $acceptedDates) ){
                array_push($bookingDates, $p->format('Y-m-d'));
            }
        }
        $code = 200;
        http_response_code($code);
        echo json_encode(array('code' => $code, 'dates' => ($bookingDates)));
    }

    public function apiGetTimesAction($site){
        $acceptedTimes = [];
        $errors = [];
        if( empty($_GET['date']) || empty($_GET['number'])){
            $code = 400;
            $errors [] = "Bad request";
        }
        else {
            $number = \App\Core\FormValidator::sanitizeData($_GET['number']);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s.u', $_GET['date'].' 23:59:59.999999');
            $today = new \DateTime();
            if($date <= $today){ //CHECK THAT USER DOESNT TRY TO RESERVE FOR A PAST DAY
                $code = 422;
                $errors[] = "Enter a valid date";
            }else {
                $bookingSettingsObj = new Booking_settings($site->getPrefix());
                $bookingSettingsObj->findOne(TRUE);

                $bookingPlanningObj = new Booking_planning($site->getPrefix());
                $bookingPlanningObj->setId($date->format('N'));
                $bookingPlanningObj->findOne(TRUE);

                if($bookingSettingsObj->getMaxNumberPerReservation() < $number){//CHECK THAT THEY DONT TRY TO RESERVE FOR MORE PERSONS THAN ALLOWED
                    $code = 422;
                    $errors [] = "You are trying to reserve for more persons than the restaurant enables. Please follow the instructions on the inputs.";
                }
                if( $bookingPlanningObj->getDisabled() == 0){//CHECK IF RESTAURANT IS WORKING THIS DAY
                    $errors[] = "We don't work this day, try another one";
                    $code = 422;
                } else { 
                    $start = \DateTime::createFromFormat('Y-m-dH:i:s', $date->format('Y-m-d').$bookingPlanningObj->getStart());
                    $end = \DateTime::createFromFormat('Y-m-dH:i:s', $date->format('Y-m-d').$bookingPlanningObj->getEnd());
                    $end->add(new \DateInterval("PT".$bookingSettingsObj->getTimePerReservation()."M")); //ADDING ONE RESERVATION SCHEDULE MORE TO GET EVEN THE LAST RESERVATION SCHEDULE
                    $period = new \DatePeriod($start, \DateInterval::createFromDateString($bookingSettingsObj->getTimePerReservation()."minutes"), $end); 
                    // CREATE A PERIOD FOR FUTURE LOOP WHICH WILL RETURN PLANNINGS THAT CAN BE BOOKED

                    $acceptedTimes = [];
                    foreach($period as $p){ // LOOPING ON PERIOD TO CHECK
                        $plan = new Booking($site->getPrefix());
                        $plan->setDate($p->format('Y-m-d H:i:s'));
                        $plans = $plan->findAll();
                        $currentReservationNumber = 0;
                        if( $plans && count($plans) > 0){ // CHECK IF THERE IS ALREADY RESERVATIONS ON THIS TIME
                            foreach( $plans as $plan){ //LOOP ON RESERVATIONS AT THIS TIME
                                $currentReservationNumber += $plan['number']; //NUMBER OF PEOPLE ALREADY BOOKED
                            }
                        }
                        if( ($currentReservationNumber + $number ) < $bookingSettingsObj->getTotalNumberPerReservation()){//IF WE DONT EXCEED THE MAX NUMBER OF RESERVATION, STOCK IT IN ARRAY
                            array_push($acceptedTimes, $p);
                        }
                    }
                    foreach($acceptedTimes as $key => $value){ //CHANGE FORMAT TO GET ACCEPTED BY FRONT
                        $acceptedTimes[$key] = $value->format('H:i:s');
                    }
                }
            }
        }
        
        if( empty($errors) ){
            $code = 200;
        }
        http_response_code($code);
        echo json_encode(array('code' => $code, 'times' => ($acceptedTimes), 'errors' => ($errors)));
    }
}