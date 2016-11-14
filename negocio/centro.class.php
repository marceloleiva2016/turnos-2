<?php
class Centro
{
    var $id;
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