<?php

include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
require_once nspcCommons . 'utils.php';

session_start();
$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);
$firePhp->log($_GET,"GET");

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction

$base = new HcaDatabaseLinker();
		

//$json = $internacion->datosResumenInternaciones($cod_centro, $mes, $anio);

$json = $base->pacientesInternadosEnUDP($page, $limit, $sidx,$sord);


echo ($json);
?>