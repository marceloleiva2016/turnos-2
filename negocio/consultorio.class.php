<?php
class Consultorio
{
    var $id;
    var $tipo_consultorio;
    var $profesional;
    var $especialidad;
    var $subespecialidad;

    function Consultorio(){
        
    }

    function getId(){
        return $this->id;
    }

    function setId($id){
        $this->id=$id;
    }

    function getTipo_consultorio(){
        return $this->tipo_consultorio;
    }

    function setTipo_consultorio($tipo_consultorio){
        $this->tipo_consultorio=$tipo_consultorio;
    }

    function getProfesional(){
        return $this->profesional;
    }

    function setProfesional($profesional){
        $this->profesional=$profesional;
    }

    function getEspecialidad(){
        return $this->especialidad;
    }

    function setEspecialidad($especialidad){
        $this->especialidad=$especialidad;
    }

    function getSubespecialidad(){
        return $this->subespecialidad;
    }

    function setSubespecialidad($subespecialidad){
        $this->subespecialidad=$subespecialidad;
    }

}