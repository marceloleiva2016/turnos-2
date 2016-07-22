<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';

$return = new stdClass();

$idprovincia=$_REQUEST['idProvincia'];
$idpais=$_REQUEST['idPais'];

$gen = new GeneralesDatabaseLinker();

$partidos = $gen->getPartidos($idpais ,$idprovincia);

if($partidos!=false) {
    $return->ret = true;

    $return->datos = $partidos;    
} else {
    $return->ret = false;
}

echo json_encode($return);