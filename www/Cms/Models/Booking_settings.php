<?php

namespace CMS\Models;

class Booking_settings extends CMSModels
{
    protected $id;
    protected $enabled;
    protected $timePerReservation;
    protected $totalNumberPerReservation;
    protected $maxNumberPerReservation;
    protected $available;
    protected $notes;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setEnabled($enabled){
        $this->enabled = $enabled;
    }

    public function getEnabled(){
        return $this->enabled;
    }

    public function setTimePerReservation($time){
        $this->timePerReservation = $time;
    }

    public function getTimePerReservation(){
        return $this->timePerReservation;
    }

    public function setTotalNumberPerReservation($number){
        $this->totalNumberPerReservation = $number;
    }

    public function getTotalNumberPerReservation(){
        return $this->totalNumberPerReservation;
    }

    public function setMaxNumberPerReservation($number){
        $this->maxNumberPerReservation = $number;
    }

    public function getMaxNumberPerReservation(){
        return $this->maxNumberPerReservation;
    }

    public function setAvailable($available){
        $this->available = $available;
    }

    public function getAvailable(){
        return $this->available;
    }
    
    public function setNotes($notes){
        $this->notes = $notes;
    }

    public function getNotes(){
        return $this->notes;
    }

}