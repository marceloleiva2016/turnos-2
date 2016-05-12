<?php

class Pais
{
    var $id;
    var $detalle;

    function Pais()
    {

    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setDetalle()
    {
        $this->detalle = $detalle;
    }
}

class Provincia  extends Pais
{
    var $idPais;

    function Provincia()
    {

    }

    function setPais($id)
    {
        $this->idPais = $id;
    }
}