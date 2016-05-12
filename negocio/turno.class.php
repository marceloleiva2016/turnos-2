<?php
class Turno
{
    var $idturno;
    var $idpaciente;
    var $fecha;
    var $hora;
    var $cod_practica;
    var $atendido;
    var $fecha_creacion;
    var $observacion;

    function Turno()
    {
        $this->idturno = null;
        $this->idpaciente = "";
        $this->fecha = "";
        $this->hora = "";
        $this->atendido = "";
        $this->fecha_creacion = "";
        $this->observacion = "";
    }

    function setIdturno($idturno)
    {
        $this->idturno = $idturno;
    }

    function setIdpaciente($idpaciente)
    {
        $this->idpaciente = $idpaciente;
    }

    function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    function setHora($hora)
    {
        $this->hora = $hora;
    }
    function setCod_practica($cod_practica)
    {
        $this->cod_practica = $cod_practica;
    }
    function setAtendido($atendido)
    {
        $this->atendido = $atendido;
    }
    function setFecha_creacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }
    function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }
    

    
}
?>