<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbDemanda = new DemandaDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

if(!isset($_REQUEST['idDestino'])  || empty($_REQUEST['idDestino']))
{
    $data->result = false;
    $data->show = true;
    $data->mensaje = "Debes seleccionar un destino para continuar";
}

if(!isset($_REQUEST['dg_diagnostico']))
{
    $data->result = false;
    $data->show = true;
    $data->message = "Debes seleccionar un diagnostico";
}

if ($data->result == true)
{
    $ingreso = $dbDemanda->insertarEgreso($_REQUEST['id'], $_REQUEST['idDestino'], $_REQUEST['dg_diagnostico'], $usuarioUnset->getId());

    if(!$ingreso)
    {
        $data->result = false;

        $data->show = true;

        $data->mensaje = "Ocurrio un error al ingresar el egreso en la atencion";
    }
}

echo json_encode($data);
?>