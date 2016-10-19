<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';

$gnls = new  GeneralesDatabaseLinker();

$anio = $_POST['ano'];

$std = new StdClass();

$std->ret = true;

if($anio!=null AND !isset($anio))
{
    $std->ret = false;
    $std->response = "";
}
else
{
    $std->response = $gnls->mesesConPacientesInternados($anio);
}

echo json_encode($std);
?>