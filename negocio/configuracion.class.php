<?php
class Configuracion
{
    var $id;
    var $idcentro;
    var $color;
    var $nombre_logo;
    var $fecha_creacion;

    function Configuracion()
    {

    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setCentro($idcentro)
    {
        $this->idcentro = $idcentro;
    }

    function getCentro()
    {
        return $this->idcentro;
    }

    function setColor($color)
    {
        $this->color = $color;
    }

    function getColor()
    {
        return $this->color;
    }

    function setNombreLogo($nombre_logo)
    {
        $this->nombre_logo = $nombre_logo;
    }

    function getNombreLogo()
    {
        return $this->nombre_logo;
    }

    function setFechaCreacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

}