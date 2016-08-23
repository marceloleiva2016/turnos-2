<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$sbesp = new  SubespecialidadDatabaseLinker();

$esp = $_POST['esp'];

$std = new StdClass();

$std->ret = true;

if($esp!=null AND !isset($esp))
{
    $std->ret = false;
    $std->response = "";
}
else
{
    $std->response = $sbesp->getSubspecialidadesConConsultoriosDeDemandaActivos($esp);
}

echo json_encode($std);
?>