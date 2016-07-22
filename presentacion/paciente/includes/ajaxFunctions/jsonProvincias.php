<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';

$return = new stdClass();

$idpais=$_REQUEST['idPais'];

$gen = new GeneralesDatabaseLinker();

$provincias = $gen->getProvincias($idpais);

if($provincias!=false) {
    $return->ret = true;

    $return->datos = $provincias;    
} else {
    $return->ret = false;
}

echo json_encode($return);