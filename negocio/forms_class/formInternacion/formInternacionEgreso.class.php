<?php
class Egreso
{
    var $id;
    var $diagnostico;
    var $tipo_egreso;
    var $fechaCreacion;
    var $usuario;

    function Egreso()
    {
        
    }

    function setId($id)
    {
        $this->idEgreso = $id;
    }

    function getId()
    {
        return $this->idEgreso;
    }

    function setDiagnostico($nombre)
    {
        $this->diagnostico = $nombre;
    }

    function getDiagnostico()
    {
        return $this->diagnostico;
    }

    function setTipoEgreso($tipo)
    {
        $this->tipo_egreso = $tipo;
    }

    function getTipoEgreso()
    {
        return $this->tipo_egreso;
    }

    function setFechaCreacion($fecha)
    {
        $this->fechaCreacion = $fecha;
    }

    function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    function setUsuario($usr)
    {
        $this->usuario = $usr;
    }

    function getUsuario()
    {
        return $this->usuario;
    }
}