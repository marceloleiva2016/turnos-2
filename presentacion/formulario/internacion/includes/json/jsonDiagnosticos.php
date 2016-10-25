<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'diagnosticoDatabaseLinker.class.php';

$dbdiag = new DiagnosticoDatabaseLinker();

$variable = $_REQUEST;

if(!isset($_REQUEST['codigo']))
{
    $codigo = NULL;
}
else
{
    $codigo = $_REQUEST['codigo'];    
}

if(!isset($_REQUEST['nombre']))
{
    $nombre = NULL;
}
else
{
    $nombre = $_REQUEST['nombre'];
}

$grupos = $dbdiag->getDiagFiltrado($codigo, $nombre);

echo json_encode($grupos);