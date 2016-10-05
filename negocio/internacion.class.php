<?php
include_once 'paciente.class.php';

class Internacion
{
    var $id;
    var $paciente;
    var $tipo_atencion_predecesora;
    var $motivo_ingreso;
    var $diagnostico;
    var $obra_social;
    var $fecha_creacion;

    public function Internacion(){

    }

    public function setId($id)
    {
        $this->id = $id;
    }    

    public function getId()
    {
        return $this->id;
    }

    public function setPaciente(Paciente $paciente)
    {
        $this->paciente = $paciente;
    }

    public function getPaciente()
    {
        return $this->paciente;
    }

    public function setTipo_atencion_predecesora($tipo_atencion_predecesora)
    {
        $this->tipo_atencion_predecesora = $tipo_atencion_predecesora;
    }

    public function getTipo_atencion_predecesora()
    {
        return $this->tipo_atencion_predecesora;
    }

    public function setMotivo_ingreso($motivo_ingreso)
    {
        $this->motivo_ingreso = $motivo_ingreso;
    }

    public function getMotivo_ingreso()
    {
        return $this->motivo_ingreso;
    }

    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;
    }    

    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    public function setObraSocial($obra_social)
    {
        $this->obra_social = $obra_social;
    }

    public function getObraSocial()
    {
        return $this->obra_social;
    }

    public function setFecha_creacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_creacion()
    {
        return $this->fecha_creacion;
    }

}