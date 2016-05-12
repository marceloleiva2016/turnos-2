<?php
include 'itemObservacion.class.php';

class Observacion
{
    private $tipo;

    private $detalle;

    private $itemsObservacion;

    function Observacion()
    {
        $itemsObservacion = array();
    }

    public function setTipo($idTipo)
    {
        $this->tipo = $idTipo;
    }

    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }

    public function agregarItem($id, $fecha, $usuario, $texto)
    {
        $this->itemsObservacion[] = new ItemObservacion($id, $fecha, $usuario, $texto);
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getDetalle()
    {
        return $this->detalle;
    }

    public function getitemsObservacion()
    {
        return $this->itemsObservacion;
    }
}