<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session de usuario expirada.";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbEpicrisis = new EpicrisisDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

$ingreso = $dbEpicrisis->actualizarCronicaIntervenciones($_REQUEST, $usuarioUnset->getId());

if($ingreso!=true)
{
    $data->result = false;

    $data->show = true;

    $data->mensaje = "Ocurrio un error al actualizar las intervenciones";
}

echo json_encode($data);
?>