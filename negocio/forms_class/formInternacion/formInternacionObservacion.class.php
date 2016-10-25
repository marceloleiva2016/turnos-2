<?php
include_once 'formInternacionItemObservacion.class.php';

class FormInternacionObservacion
{
    private $tipo;

    private $detalle;

    private $itemsObservacion;

    function FormInternacionObservacion()
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
        $this->itemsObservacion[] = new FormInternacionItemObservacion($id, $fecha, $usuario, $texto);
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