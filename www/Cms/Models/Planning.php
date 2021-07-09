<?php

namespace CMS\Models;

class Planning extends CMSModels
{
    protected $id;
    protected $day;
    protected $start;
    protected $end;
    protected $notes;

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
}