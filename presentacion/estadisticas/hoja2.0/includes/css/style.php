<?php
include_once '../../../../namespacesAdress.php';
include_once conexion.'conectionData.php';
include_once datos.'configuracionDatabaseLinker.class.php';

session_start();

$cnfDb = new ConfiguracionDatabaseLinker();

$configuracion = $cnfDb->getConfiguracion($_SESSION['centro']);

header('content-type:text/css');
 
$colorPrincipal = $configuracion->getColor();

echo <<<FINCSS

table {
    border-collapse: collapse;
}

table, th, td {
    border: 0px solid black;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 0px solid #ddd;
}

th {
    height: 50px;
    text-align: center;
    vertical-align: bottom;
    background-color: $colorPrincipal;
    color: white;
}

tr:hover {background-color: #f5f5f5}

tr:nth-child(even) {background-color: #f2f2f2}

.page {
    margin: 50px auto;
    text-align: center;
    height: 350px;
    width: 500px;
    background: $colorPrincipal;
    border: 1px solid rgba(0,0,0,.05);
    box-shadow: 0px 0px 0px 20px rgba(0,0,0,0.03);
}


FINCSS;
?>