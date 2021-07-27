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
    protected $isSetUp;

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

    public function setIsSetUp($isSetUp){
        $this->isSetUp = $isSetUp;    
    }

    public function getIsSetUp(){
        return $this->isSetUp;
    }

    public function form(){
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"form_content",
                "class"=>"form-content",
                "submit"=>"edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem"
            ],
            "inputs"=>[
                "enabled"=>[
                    "type"=>"radio",
                    "label"=>"enable reservations",
                    "minLength"=>1,
                    "maxLength"=>1,
					"options" => [
						0 => "no",
						1 => "yes"
					],
                    "class"=>"input-content",
                    "placeholder"=>"available",
                    "error"=>"You need to specify if you are enabled!",
                    "required"=>true,
                    "value"=>$this->getEnabled()
                ],
                "timePerReservation"=>[
                    "type"=>"number",
                    "label"=>"average time for every reservation in minutes (ex: 30)",
                    "id"=>"timePerReservation",
                    "class"=>"input-content",
                    "required"=>true,
                    "placeholder"=>"30",
                    "value"=>$this->getTimePerReservation()
                ],
                "totalNumberPerReservation"=>[
                    "type"=>"number",
                    "label"=>"max number of people that can reserve per schedule",
                    "id"=>"totalNumberPerReservation",
                    "class"=>"input-content",
                    "required"=>true,
                    "placeholder"=>"30",
                    "value"=>$this->getTotalNumberPerReservation()
                ],
                "maxNumberPerReservation"=>[
                    "type"=>"number",
                    "label"=>"max number of people that can reserve at once",
                    "id"=>"maxNumberPerReservation",
                    "class"=>"input-content",
                    "required"=>true,
                    "placeholder"=>"30",
                    "value"=>$this->getMaxNumberPerReservation()
                ],
                "available"=>[
                    "type"=>"radio",
                    "label"=>"available for reservation ?",
                    "minLength"=>1,
                    "maxLength"=>1,
					"options" => [
						0 => "no",
						1 => "yes"
					],
                    "class"=>"input-content",
                    "placeholder"=>"available",
                    "error"=>"You need to specify if you are available!",
                    "required"=>true,
                    "value"=>$this->getAvailable()
                ],
                "notes"=>[
                    "type"=>"text",
					"label"=>"notes",
					"id"=>"notes",
					"class"=>"input-notes",
                    "placeholder"=>"Notes",
                    "value"=>$this->getNotes()
                ]
            ]
        ];
    }

}