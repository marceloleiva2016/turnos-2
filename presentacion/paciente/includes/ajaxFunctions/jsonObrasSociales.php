<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'obraSocialDatabaseLinker.class.php';

$dbOsoc = new ObraSocialDatabaseLinker();

$variable = $_REQUEST;

if(!isset($_REQUEST['Det']))
{
    $nombre = "";
}
else
{
    $nombre = $_REQUEST['Det'];
}

$obrasSociales = $dbOsoc->getOsocFiltrada($nombre);

echo json_encode($obrasSociales);