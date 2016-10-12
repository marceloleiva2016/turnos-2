<?php
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once negocio.'profesional.class.php';
include_once negocio.'paciente.class.php';
include_once 'formInternacionObservacion.class.php';
include_once 'formInternacionEgreso.class.php';

class FormInternacion
{
    var $idatencion;
    var $id;
    var $paciente;
    var $observaciones;
    var $egreso;
    var $fecha;
    var $idusuario;
    var $estudiosLaboratorioSangre;
    var $estudiosLaboratorioOrina;
    

    function FormInternacion()
    {
        $this->observaciones = array();
        $this->estudiosLaboratorioOrina = array();
        $this->estudiosLaboratorioSangre = array();
    }

    function setEstudiosLaboratorioOrina($estudios)
    {
        $this->estudiosLaboratorioOrina = $estudios;
    }

    function getEstudiosLaboratorioOrina()
    {
        return $this->estudiosLaboratorioOrina;
    }

    function setEstudiosLaboratorioSangre($estudios)
    {
        $this->estudiosLaboratorioSangre = $estudios;
    }

    function getEstudiosLaboratorioSangre()
    {
        return $this->estudiosLaboratorioSangre;
    }

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getIdAtencion()
    {
        return $this->idatencion;
    }

    function setIdAtencion($idAtencion)
    {
        $this->idatencion = $idAtencion;
    }

    function agregarObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    function getObservaciones()
    {
        return $this->observaciones;
    }

    function setPaciente(Paciente $pac)
    {
        $this->paciente = $pac;
    }

    function getPaciente()
    {
        return $this->paciente;
    }

    function setUsuario($id)
    {
        $this->idusuario = $id;
    }

    function fechaCreacion($fecha)
    {
        $this->fecha = $fecha;
    }

    function setEgreso(Egreso $egreso)
    {
        $this->egreso = $egreso;
    }

    function getEgreso()
    {
        return $this->egreso;
    }
}