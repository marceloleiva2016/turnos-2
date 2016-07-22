<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';

$return = new stdClass();

$idPartido=$_REQUEST['idPartido'];
$idprovincia=$_REQUEST['idProvincia'];
$idpais=$_REQUEST['idPais'];

$gen = new GeneralesDatabaseLinker();

$Localidades = $gen->getLocalidades($idpais, $idprovincia, $idPartido);

if($Localidades!=false) {
    $return->ret = true;

    $return->datos = $Localidades;    
} else {
    $return->ret = false;
}

echo json_encode($return);