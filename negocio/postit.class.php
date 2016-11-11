<?php

class Postit
{
    private $id;
    private $descripcion;
    private $usuario;

    function Postit()
    {
        $usuario = new Usuario();
    }
    
    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function getDescripcion()
    {
        return $this->descripcion;
    }

    function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    function getUsuario()
    {
        return $this->usuario;
    }

    function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

}