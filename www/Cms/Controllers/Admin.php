<?php

namespace CMS\Controller;

use App\Models\User;

use CMS\Core\CMSView as View;
use CMS\Models\Booking;

class Admin{

	private function getPendingBooking($prefix){
		$bookingObj = new Booking($prefix);
		$bookingObj->setStatus('IS FALSE');
		$booking = $bookingObj->findAll();
		$list = [];

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
					$item['date'] = (new \DateTime())->format('d/m/Y');
					$item['hour'] = (new \DateTime())->format('H:i:s');
					$item['client'] = ($client->getFirstname(). ' ' . $client->getLastname());
					$list[] = $item;
				}
			}
		}
		return $list;
	}

	private function getCurrentBooking($prefix){
		$bookingObj = new Booking($prefix);
		$bookingObj->setStatus(1);
		$booking = $bookingObj->findAll();
		$list = [];

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
					$item['date'] = (new \DateTime())->format('d/m/Y');
					$item['hour'] = (new \DateTime())->format('H:i:s');
					$item['client'] = ($client->getFirstname(). ' ' . $client->getLastname());
					$list[] = $item;
				}
			}
		}
		return $list;
	}

	public function defaultAction($site){
		$pendingBooking = $this->getPendingBooking($site->getPrefix());
		$currentBooking = $this->getCurrentBooking($site->getPrefix());

		$html = 'Default admin action on CMS <br>';
		$html .= 'We\'re gonna assume that you are the site owner <br>'; 
		$view = new View('dashboard', 'back',  $site);
		$view->assign('pageTitle', "Dashboard");
		$view->assign('content', $html);
		$view->assign('pendingBooking', $pendingBooking );
		$view->assign('currentBooking', $currentBooking );

	}

}