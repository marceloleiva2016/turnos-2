<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once 'observacion.class.php';

class Pendiente extends Observacion{
	
	/*var $id;
	var $descripcion;
	var $fecha;
	var $usuario;*/
	static $TIPO =TipoObservacion::PENDIENTE;
	
	
	function Pendiente($descrip) {
		//parent::_construct($descrip);
		$this->descripcion = $descrip;
		$this->tipoObservacion= TipoObservacion::PENDIENTE;
	}
	/*
	function getHTMLDate()
	{
		return Utils::phpTimestampToHTMLDate($this->fecha);
	}
	
	function nombreUsuario()
	{
		$generales = new GeneralesDataBaseLinker();
		
		return $generales->nombreUsuario($this->usuario);
	}*/
}
?>