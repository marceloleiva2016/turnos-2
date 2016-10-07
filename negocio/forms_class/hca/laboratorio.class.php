<?php

include_once '/home/web/namespacesAdress.php';
include_once 'tipoLaboratorio.class.php';

class Labortatorio {
	var $nombre;
	var $descripcion;
	var $valor;
	var $tipoLaboratorio;
	var $id;
	var $esNumerico;
	
	function Labortatorio() {
		$this->tipoLaboratorio = new TipoLaboratorio();
	}
	
}
?>