<?php

namespace CMS\Models;

class Booking_planning extends CMSModels
{
    protected $id;
    protected $day;
    protected $start;
    protected $end;
    protected $notes;
    protected $disabled;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setDay($day){
        $this->day = $day;
    }

    public function getDay(){
        return $this->day;
    }

    public function setStart($start){
        $this->start = $start;
    }

    public function getStart(){
        return $this->start;
    }

    public function setEnd($end){
        $this->end = $end;
    }

    public function getEnd(){
        return $this->end;
    }

    public function setNotes($notes){
        $this->notes = $notes;
    }

    public function getNotes(){
        return $this->notes;
    }

    public function setDisabled($disabled){
        $this->disabled = $disabled;
    }

    public function getDisabled(){
        return $this->disabled;
    }

    public function individualInput(){//CREATE AND INPUT ASSOCIATED TO THE ID IN DB
        return [
            "day-".$this->getId()=>[
                "type"=>"text",
                "id"=>$this->getDay(),
                "class"=>"input-content",
                "placeholder"=>$this->getDay(),
                "disabled"=>true,
                "value"=>$this->getDay()
            ],
            "start-".$this->getId()=>[
                "type"=>"time",
                "label"=>'Start of the day',
                "id"=>"start",
                "class"=>"input-content",
                "placeholder"=>"Start of the day",
                "required"=>true,
                "value"=>$this->getStart()
            ],
            "end-".$this->getId()=>[
                "type"=>"time",
                "label"=>'End of the day',
                "id"=>"end",
                "class"=>"input-content",
                "placeholder"=>"End of the day",
                "required"=>true,
                "value"=>$this->getEnd()
            ],
            "notes-".$this->getId()=>[
                "type"=>"text",
                "label"=>"notes",
                "id"=>"notes",
                "class"=>"input-notes",
                "placeholder"=>"Notes",
                "value"=>$this->getNotes()
            ],
            "disabled-".$this->getId()=>[
                "type"=>"radio",
                "label"=>"working ?",
                "minLength"=>1,
                "maxLength"=>1,
                "options" => [
                    0 => "no",
                    1 => "yes"
                ],
                "class"=>"input-content",
                "placeholder"=>"working",
                "error"=>"You need to specify if you are working!",
                "required"=>true,
                "value"=>$this->getDisabled()
            ]
        ];
    }
    public function form($forms){//GET AN ARRAY OF INPUTS TO CREATE A FORM WITH ALL THE INPUTS
        $inputs = [];
        foreach($forms as $form){
            $inputs = array_merge($inputs, $form->individualInput());
        }
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "id"=>"planning_form",
                "class"=>"form-content",
                "submit"=>"edit",
                "submitClass"=>"cta-blue width-80 last-sm-elem",
                "name" => "planning-form"
            ],
            "inputs"=>$inputs
        ];
    }
}