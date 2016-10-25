<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once 'salida.class.php';

class SalidaUDP extends Salida {
	
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
	
	
	
	function SalidaUDP() {
		parent::Salida();
	}
	
	/**
	 * 
	 * Devuelve la descripcion del destino
	 * @return string
	 */
	public function destinoToString() {
		
		return (TipoDestinoUDP::toString($this->destino));
	}
	
}
?>