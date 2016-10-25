<?php
class Cama
{
    var $id;
    var $nro_cama;
    var $sector;
    var $fecha_creacion;
    var $id_internacion;

    public function Cama()
    {

    }

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id=$id;
    }

    function getNro()
    {
        return $this->nro_cama;
    }

    function setNro($nro_cama)
    {
        $this->nro_cama=$nro_cama;
    }

    function getSector()
    {
        return $this->sector;
    }

    function setSector($sector)
    {
        $this->sector = $sector;
    }

    function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    function setFechaCreacion($fecha_creacion)
    {
        $this->fecha_creacion=$fecha_creacion;
    }

    function setIdInternacion($idInternacion)
    {
        $this->id_internacion=$idInternacion;
    }

    function getIdInternacion()
    {
        return $this->id_internacion;
    }
}