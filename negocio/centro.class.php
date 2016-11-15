<?php
class Centro
{
    var $id;
    var $codigo_centro;
    var $detalle;
    var $direccion;
    var $telefono;

    public function Centro()
    {

    }

    function setId($id)
    {
        $this->id= $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setCodigoCentro($codigo_centro)
    {
        $this->codigo_centro = $codigo_centro;
    }

    function getCodigoCentro()
    {
        return $this->codigo_centro;
    }

    function setDetalle($detalle)
    {
        $this->detalle= $detalle;
    }

    function getDetalle()
    {
        return $this->detalle;
    }

    function setDireccion($direccion)
    {
        $this->direccion= $direccion;
    }

    function getDireccion()
    {
        return $this->direccion;
    }

    function setTelefono($telefono)
    {
        $this->telefono= $telefono;
    }

    function getTelefono()
    {
        return $this->telefono;
    }

}