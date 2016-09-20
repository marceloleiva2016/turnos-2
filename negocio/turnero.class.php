<?php
include_once 'consultorio.class.php';

class Turnero {

    var $id;
    var $fecha_creacion;
    var $idusuario;
    var $consultorios;

    function Turnero(){
        $this->consultorios = array();
    }

    function getId(){
        return $this->id;
    }

    function setId($id){  
        $this->id = $id;
    }

    function getFechaCreacion(){
        return $this->fecha_creacion;
    }

    function setFechaCreacion($fechaCreacion){
        $this->fecha_creacion = $fechaCreacion;
    }

    function getIdUsuario(){
        return $this->idusuario;
    }

    function setIdUsuario($usuario){
        $this->idusuario = $usuario;
    }

    function getConsultorios(){
        return $this->consultorios;
    }

    function setConsultorios($consultorios){
        $this->consultorios = $consultorios;
    }
}
?>