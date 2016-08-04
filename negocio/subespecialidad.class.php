<?php
include_once 'especialidad.class.php';

class Subespecialidad extends Especialidad
{
    var $especialidad;
    var $habilitado;
    var $idusuario;

    function Subespecialidad()
    {
        $this->id = null;
        $this->detalle = "";
        $this->especialidad = "";
        $this->habilitado = null;
        $this->idusuario = "";

    }

    function setEspecialidad($value)
    {
        $this->especialidad = $value;
    }

    function setHabilitado($value)
    {
        $this->habilitado = $value;
    }

    function setIdusuario($value)
    {
        $this->idusuario = $value;
    }

    function getEspecialidad()
    {
        return $this->especialidad;
    }

}