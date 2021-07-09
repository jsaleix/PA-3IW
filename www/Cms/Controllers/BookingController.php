<?php

namespace CMS\Controller;
use CMS\Models\Booking;

class BookingController{

    public function manageBookingsAction($site){
        $bookingObj = new Booking($site['prefix']);
    }

    public function addBookingAction($site){
        $bookingObj = new Booking($site['prefix']);
    }
}