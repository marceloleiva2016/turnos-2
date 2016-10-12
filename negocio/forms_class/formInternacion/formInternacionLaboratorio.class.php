<?php
include_once 'formInternacionTipoLaboratorio.class.php';

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