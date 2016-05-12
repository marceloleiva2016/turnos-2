<?php
class DemandaItemObservacion
{
    var $id;
    var $fecha;
    var $usuario;
    var $texto;

    public function DemandaItemObservacion($id, $fecha, $usuario, $texto)
    {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->usuario = $usuario;
        $this->texto = $texto;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDetalle()
    {
        return $this->texto;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
}