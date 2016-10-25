<?php

class Sector {
    var $id;
    var $detalle;
    var $especialidad;

    public function Sector() {

    }

    public function setId($id){
        $this->id = $id;
    }

    public function setDetalle($detalle){
        $this->detalle = $detalle;
    }

    public function setEspecialidad($detalle){
        $this->especialidad = $detalle;
    }

    public function getId(){
        return $this->id;
    }

    public function getDetalle(){
        return $this->detalle;
    }

    public function getEspecialidad()
    {
        return $this->especialidad;
    }
}