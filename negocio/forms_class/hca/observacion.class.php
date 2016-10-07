<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once 'tipoObservacion.class.php';

class Observacion {
	
	var $id; //Agregado es util para editar y modificar
	var $descripcion;
	var $fecha;
	var $usuario;
	var $tipoObservacion;
	static $TIPO = TipoObservacion::OBSERVACION;
	
	function Observacion($descrip) {
		$this->descripcion = $descrip;
		$this->tipoObservacion = TipoObservacion::OBSERVACION;
	}
	
	function getHTMLDate()
	{
		return Utils::phpTimestampToHTMLDate($this->fecha);
	}
	
	function nombreUsuario()
	{
		$generales = new GeneralesDataBaseLinker();
		
		return $generales->nombreUsuario($this->usuario);
	}
}
?>