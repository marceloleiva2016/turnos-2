<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once 'tipoDestino.class.php';

abstract class Salida {
	
	/**
	 * 
	 * codigo de diagnostico
	 * @var int
	 */
	var $diagnostico;
	
	/**
	 * 
	 * Fecha en la que se creo la salida
	 * @var int
	 */
	var $fecha;
	
	/**
	 * 
	 * Profesional que firmo la salida  
	 * @var int
	 */
	var $profesional;
	
	/**
	 * 
	 * Indica el destino a donde se deriva el paciente
	 * @var int
	 */
	var $destino;
	
	/**
	 * 
	 * Indica el id del diagnostico de egreso cuando este existe en la base de datos
	 * @var int
	 */
	var $id;
	/**
	 * 
	 * Denota el usuario que dio el egreso al paciente
	 * @var string
	 */
	var $usr;
	
	function Salida() {
		
	}
	
	function getHTMLDate()
	{
		return Utils::phpTimestampToHTMLDate($this->fecha);
	}
	
	function nombreProfesional()
	{
		$generales = new GeneralesDataBaseLinker();
		
		return $generales->nombreProfesional($this->profesional);
	}

	/**
	 * 
	 * Devuelve la descripcion del diagnostico
	 * @return string
	 */
	public function diagnosticoToString()
	{
		$generales = new GeneralesDataBaseLinker();
		return $generales->diagnosticoFromAll($this->diagnostico);
	}	
}