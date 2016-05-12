<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    header ("Location: /epicrisis/index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbEpicrisis = new EpicrisisDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

$ingreso = $dbEpicrisis->ingresarAntecedente($_REQUEST['id'], $_REQUEST['ant'], $_REQUEST['detalleAnt'], $usuarioUnset->getId());

if($ingreso!=true)
{
    $data->result = false;

    $data->show = true;

    $data->mensaje = "Ocurrio un error al ingresar el antecedente";
}

echo json_encode($data);
?>