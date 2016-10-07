<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';

class SalidaHS extends Salida {
	
	/**
	 * 
	 * el centro al que es derivado en caso de ser una derivacion interna
	 * @var int
	 */
	var $centro;
	
	/** 
	 * 
	 * el servicio al que es derivado en caso de ser una internacion
	 * @var int
	 */
	var $servicio;

	/**
	 * 
	 * el sector fisico en donde esta el paciente al momento de cargar la salida 
	 * @var int
	 */
	var $sectorFisico;
	
	
	function SalidaHS() {
		parent::Salida();
	}
	
	/**
	 * 
	 * Devuelve la descripcion del destino
	 * @return string
	 */
	public function destinoToString() {
		return (TipoDestinoHS::toString($this->destino));
	}
	
}
?>