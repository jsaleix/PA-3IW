<?php

namespace CMS\Controller;
use CMS\Models\Booking_planning;

class PlanningController{
    
    public function managePlanningAction($site){
        $planningObj = new Booking_planning($site['prefix']);
        $plannings = $planningObj->findAll();
        print_r($plannings);
    }

    public function createPlanningAction($site){
        $planningObj = new Booking_planning($site['prefix']);
    }
}