<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbformInt = new FormInternacionDatabaseLinker();
$dbInternacion = new InternacionDatabaseLinker();
$dbAtencion = new AtencionDatabaseLinker();

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
    $ingreso = $dbformInt->insertarEgreso($_REQUEST['id'], $_REQUEST['idDestino'], $_REQUEST['dg_diagnostico'], $usuarioUnset->getId());
    $idAtencion = $dbformInt->getIdAtencion($_REQUEST['id']);
    $idInternacion = $dbAtencion->obtenerIdTurno($idAtencion);
    $dbInternacion->salidaAInternacion($idInternacion, $usuarioUnset->getId());
    //ingreso en el log el cambio de estado de internacion

    if(!$ingreso)
    {
        $data->result = false;

        $data->show = true;

        $data->mensaje = "Ocurrio un error al ingresar el egreso en la atencion";
    }
}

echo json_encode($data);
?>