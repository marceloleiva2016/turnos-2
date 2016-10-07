<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'laboratorio.class.php';


$tipoDoc = Utils::postIntToPHP($_POST['tipoDoc']);
$nroDoc = Utils::postIntToPHP($_POST['nroDoc']);

$base = new HcaDatabaseLinker();
echo $base->jsonUDPsPaciente($tipoDoc, $nroDoc);
?>