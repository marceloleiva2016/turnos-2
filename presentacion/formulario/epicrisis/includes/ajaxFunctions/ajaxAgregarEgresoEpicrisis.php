<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    header ("Location: /epicrisis/index.php?logout=1");
    die();
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbEpicrisis = new EpicrisisDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

    $ingreso = $dbEpicrisis->ingresarEgreso($_REQUEST['id'], $_REQUEST['idDestino'], $_REQUEST['diagnosticoid'], $usuarioUnset->getId());

    if(!$ingreso)
    {
        $data->result = false;

        $data->show = true;

        $data->mensaje = "Ocurrio un error al ingresar el egreso en la epicrisis";
    }   


echo json_encode($data);
?>