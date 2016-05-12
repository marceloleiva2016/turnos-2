<?php
class Profesional
{
    var $id;
    var $nombre;
    var $apellido;
    var $matricula_n;
    var $matricula_p;
    var $email;
    var $telefono;
    var $idusuario;
    var $habilitado;


    function Profesional()
    {
        $this->id = null;
        $this->nombre = "";
        $this->apellido = "";
        $this->matricula_n = "";
        $this->matricula_p = "";
        $this->email = "";
        $this->telefono = "";
        $this->idusuario = null;
        $this->habilitado = null;

    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    function setMatriculaN($matN)
    {
        $this->matricula_n = $matN;
    }

    function setMatriculaP($matP)
    {
        $this->matricula_p = $matP;
    }

    function setEmail($em)
    {
        $this->email = $em;
    }

    function setTelefono($tel)
    {
        $this->telefono = $tel;
    }

    function setIdusuario($idusu)
    {
        $this->idusuario = $idusu;
    }

    function setHabilitado($hab)
    {
        $this->habilitado = $hab;
    }


}
?>