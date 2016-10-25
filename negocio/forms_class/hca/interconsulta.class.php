<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once 'observacion.class.php';

class Interconsulta extends Observacion{
	
	
	static $TIPO =TipoObservacion::INTERCONSULTA;
	
	function Interconsulta($descrip) {
		//parent::_construct($descrip);
		$this->descripcion = $descrip;
		$this->tipoObservacion= TipoObservacion::INTERCONSULTA;
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