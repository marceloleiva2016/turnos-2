<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$dbEpicrisis = new EpicrisisDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

$ingreso = $dbEpicrisis->ingresarEstadoIngreso($_REQUEST['id'], $_REQUEST['idEstadoIngreso']);

if($ingreso!=true)
{
    $data->result = false;

    $data->show = true;

    $data->mensaje = "Ocurrio un error al ingresar el estado de ingreso";
}

echo json_encode($data);
?>