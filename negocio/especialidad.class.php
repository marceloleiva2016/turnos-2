<?php
class Especialidad
{
    var $id;
    var $detalle;

    function Especialidad()
    {
        
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }

    function getId()
    {
        return $this->id;
    }

    function getDetalle()
    {
        return $this->detalle;
    }
}