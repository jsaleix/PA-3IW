<?php

namespace CMS\Controller;

use App\Core\FormValidator;
use App\Models\User;
use App\Models\Mail;

use CMS\Core\CMSView as View;
use CMS\Models\Booking;
use CMS\Models\Booking_settings;
use CMS\Models\Booking_planning as Planning;

class BookingSettingsController{
    
    public function manageBookingSettingsAction($site){
        $bookingSettingsObj = new Booking_settings($site->getPrefix());
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

    public function editSettingsAction($site){
        $bookingSettingsObj = new Booking_settings($site->getPrefix());//CREATE VIEW AND FORM TO EDIT THE BOOKING SETTINGS
        $bookingSettingsObj->findOne(TRUE);
        $formEdit = $bookingSettingsObj->form();
        $view = new View('booking', 'back', $site);
        $view->assign('pageTitle', "Manage the events");
        $view->assign("form", $formEdit);
        $view->assign("settings", true);
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
                \App\Core\Helpers::customRedirect('/admin/booking/edit/settings', $site);
            } else {
                $errors[] = "Cannot set these settings";
                $view->assign("errors", $errors);
            }
        }
    }

    public function editPlanningsAction($site){
        $planObj = new Planning($site->getPrefix());//CREATE A PLANNING OBJ AND GET ALL DAYS FROM THE WEEK FROM DB
        $plans = $planObj->findAll();
        $forms = [];
        if( $plans && count($plans) > 0){
            foreach($plans as $p){//STOCK THE DAYS ON AN ARRAY TO RENDER THE FORM FOR EVERY DAY IN VIEW
                $plan = new Planning($site->getPrefix());
                $plan->populate($p);//POPULATE AN OBJECT WITH THE ARRAY VALUES
                $forms[] = $plan;
            }
            $fieldNumber = count($plans);
        }

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
                    $planObj = new Planning($site->getPrefix());//WE CREATE A NEW PLAN OBJ
                    $savedPlans = array_slice($_POST, $i - ($totalPost/$fieldNumber) , ($totalPost/$fieldNumber));//AND SAVE THE 4 INPUTS IN OUR ARRAY
                    $this->sanatizeAssociativeKeys($savedPlans, "-".$index);//SANATIZE THE DATA OF OUR ARRAY
                    $savedPlans[ "id" ] = $index ;//SET ARRAY ID TO USE THE EDIT DB FUNCTION
                    $pdoResult = $planObj->edit($savedPlans);//TRY TO EDIT THE DAY CURRENTLY ASSOCIATED
                    if( !$pdoResult ){
                        $errors = "Booking planning not modified!";
                        $view->assign("errors", $errors);
                        break;
                    }
                    $index++;//INCREMENT THE INDEX TO PASS TO THE NEXT OBJECT WHEN WE ENTER IN THE IF
                }
            }
            $bsObj = new Booking_settings($site->getPrefix());//MODIFY THE BOOKING SETTINGS TO UNDERSTAND THAT THE PLANNINGS WERE SET UP
            $bsObj->setIsSetUp(1);
            $bsObj->save();
            $message = "Booking planning created !";
            $view->assign("message", $message);
            \App\Core\Helpers::customRedirect('/admin/booking/edit/planning', $site);
            return;
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
        $planObj = new Planning($site->getPrefix());//CREATE A PLANNING OBJ AND GET ALL DAYS FROM THE WEEK FROM DB
        $plans = $planObj->findAll();
        $forms = [];
        if( $plans && count($plans) > 0){
            foreach($plans as $p){//STOCK THE DAYS ON AN ARRAY TO RENDER THE FORM FOR EVERY DAY IN VIEW
                $plan = new Planning($site->getPrefix());
                $plan->populate($p);//POPULATE AN OBJECT WITH THE ARRAY VALUES
                $forms[] = $plan;
            }
            $fieldNumber = count($plans);
        }

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
                    $planObj = new Planning($site->getPrefix());//WE CREATE A NEW PLAN OBJ
                    $savedPlans = array_slice($_POST, $i - ($totalPost/$fieldNumber) , ($totalPost/$fieldNumber));//AND SAVE THE 4 INPUTS IN OUR ARRAY
                    $this->sanatizeAssociativeKeys($savedPlans, "-".$index);//SANATIZE THE DATA OF OUR ARRAY
                    $savedPlans[ "id" ] = $index ;//SET ARRAY ID TO USE THE EDIT DB FUNCTION
                    $pdoResult = $planObj->edit($savedPlans);//TRY TO EDIT THE DAY CURRENTLY ASSOCIATED
                    if( !$pdoResult ){
                        $errors = "Booking planning not modified!";
                        $view->assign("errors", $errors);
                        break;
                    }
                    $index++;//INCREMENT THE INDEX TO PASS TO THE NEXT OBJECT WHEN WE ENTER IN THE IF
                }
            }
            $bsObj = new Booking_settings($site->getPrefix());//MODIFY THE BOOKING SETTINGS TO UNDERSTAND THAT THE PLANNINGS WERE SET UP
            $bsObj->setId($bookingSettingsObj->getId());
            $bsObj->setIsSetUp(1);
            $bsObj->save();
            $message = "Booking planning created !";
            $view->assign("message", $message);
            \App\Core\Helpers::customRedirect('/admin/booking', $site);
            return;
        }
    }

    private function sanatizeAssociativeKeys(&$array, $sanatizer){//THE "&" IS TO SAVE THE MODIFICATIONS ON THE VARIABLE THAT IS PAST TO THE FUNCTION, AS A POINTEUR WOULD WORK
        foreach($array as $key => $value){//GET OFF THE SANATIZER FROM EVERY KEYS IN THE ARRAY,
            $newKey = str_replace($sanatizer, "", $key);
            $array[$newKey] = $array[$key];
            unset($array[$key]);
        }
    }

    private function setupCalendar($site, $bookingSettingsObj){
        $bookingObj = new Booking($site->getPrefix());//TRY TO FIND RESERVATIONS FOR THE RESTAURANT

        //Get all the accepted bookings date
            $bookingObj->setStatus(1);
            $booking = $bookingObj->findAll();
        
            $accepted = [];
            $accepted['fields'] = [ 'client', 'date', 'number' ];
            $accepted['data'] = [];
            if($booking){
                foreach($booking as $item){
                    $date  = new \DateTime($item["date"]);
                    $today = new \DateTime();
                    if($date < $today)  //Check if the date is not past, if yes updates the booking item status to 2 in order to mean it's gone
                    {
                        $bookDate = new Booking($site->getPrefix());
                        $bookDate->setId($item['id']);
                        $bookDate->setStatus(2);
                        $bookDate->save();
                    }else{
                        $client = new User();
                        $client->setId($item['client']);
                        $client->findOne(TRUE);
                        $formalized = "'" . ($client->getFirstname(). ' ' . $client->getLastname()) . "','" . $item['date'] . "','" . $item['number'] . "'";
                        $accepted['data'][] = $formalized;
                    }
                }
            }
        //----//

        //Get all the pending bookings date
            $bookingObj->setStatus("IS FALSE");
            $booking = $bookingObj->findAll();

            $pendings = [];
            $pendings['fields'] = [ 'client', 'date', 'number', 'accept', 'delete' ];
            $pendings['data'] = [];

            if($booking){//IF THERE IS RESERVATIONS WAITING, STORE THEM IN AN ARRAY TO SHOW THEM ON FRONT
                foreach($booking as $item){
                    $client = new User();
                    $client->setId($item['client']);
                    $client->findOne(TRUE);
                    $accept = \App\Core\Helpers::renderCMSLink( "admin/booking/accept?id=".$item['id'], $site);
                    $decline = \App\Core\Helpers::renderCMSLink( "admin/booking/decline?id=".$item['id'], $site);
                    $buttonAccept = '<a href="' . $accept . '">Go</a>';
                    $buttonDelete = '<a href="' . $decline . '">Go</a>';
                    $formalized = "'" . ($client->getFirstname(). ' ' . $client->getLastname()) . "','" . $item['date'] . "','" . $item['number'] .  "','" . $buttonAccept . "','" . $buttonDelete . "'";
                    $pendings['data'][] = $formalized;
                }
            }
        //----//

        $view = new View('booking.list', 'back', $site);
		$view->assign("pendings", $pendings);
		$view->assign("accepted", $accepted);
		$view->assign('pageTitle', "Manage the comments");
        $view->assign('calendar', true);
    }

    public function acceptBookingAction($site){//CHECK IF AN ID IS GIVEN
        if(!isset($_GET['id']) || empty($_GET['id']) ){
			echo 'Booking not found ';
			exit();
		}
        $bookingObj = new Booking($site->getPrefix());
        $bookingObj->setId($_GET['id']);
        if( $bookingObj->findOne(TRUE)){//IF WE FIND A RESERVATION WITH THIS ID, ACCEPT IT
            $bookingObj->setStatus(1);
            $bookingObj->save();
            $this->sendAcceptMail($bookingObj, $site);
        }
        \App\Core\Helpers::customRedirect('/admin/booking', $site);
    }

    public function deleteBookingAction($site){//CHECK IF AN ID IS GIVEN
        try{
            if(!isset($_GET['id']) || empty($_GET['id']) ){
                throw new \Exception('No id set');
            }
            $bookingObj = new Booking($site->getPrefix());
            $bookingObj->setId($_GET['id']);
            if( !$bookingObj->findOne(TRUE)){//IF WE FIND A RESERVATION WITH THIS ID, DELETE IT, WE DONT HAVE A REFUSED STATUS FOR THE MOMENT
                throw new \Exception('Booking date not found');
            }
            $delete = $bookingObj->delete();
            $this->sendDeclineMail($bookingObj, $site);
            if(!$delete){
                throw new \Exception('Couldn\'t delete this booking');
            }
        }catch(\Exception $e){
            \App\Core\Helpers::customRedirect('/admin/booking?delete=failed', $site);
        }
        \App\Core\Helpers::customRedirect('/admin/booking?delete=success', $site);
    }

    public function sendAcceptMail($booking, $site){
        $receiver = new User();
		$receiver->setId($booking->getClient());
		$receiver->findOne(TRUE);

		$body = "<h3>EasyMeal</h3><br>";
		$body .= "<h2> Your reservation for the date : ". $booking->getDate(). " has been accepted</h2>";
		$body .= "<hr>";
		$body .= "<p>We mind to remember you that you reserved for ". $booking->getNumber(). " persons, if you come with more people, the restaurant has the right to decline your entrance. Kind regards</p>";
		$mail = array( 'from' => 'EasyMeal', 'to' => $receiver->getEmail(), 'subject' => "Your reservation for ". $site->getName(). " has been accepted" , 'body' => $body);
		$mailer = new Mail();
		$mailer->sendMail($mail);
    }

    public function sendDeclineMail($booking, $site){
        $receiver = new User();
		$receiver->setId($booking->getClient());
		$receiver->findOne(TRUE);

		$body = "<h3>EasyMeal</h3><br>";
		$body .= "<h2> We're sorry but the restaurant couldn't accept your reservations, if you want more information you can contact them !</h2>";
		$body .= "<hr>";
		$mail = array( 'from' => 'EasyMeal', 'to' => $receiver->getEmail(), 'subject' => "Your reservation for ". $site->getName(). " has been declined" , 'body' => $body);
		$mailer = new Mail();
		$mailer->sendMail($mail);
    }
}