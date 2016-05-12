<?php
include_once 'especialidad.class.php';

class Subespecialidad extends Especialidad
{
    var $idespecialidad;

    function Subespecialidad()
    {
        
    }

    function setEspecialidad($value)
    {
        $this->idespecialidad = $value;
    }
}