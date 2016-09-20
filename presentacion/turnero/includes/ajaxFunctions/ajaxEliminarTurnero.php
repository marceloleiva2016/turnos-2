<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
    die();
}

$data = $_REQUEST;

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$dbTurnero = new TurneroDatabaseLinker();

$arr= array();

$ret = $dbTurnero->eliminarTurneros($data['id'],$usuarioUnset->getId());

echo json_encode($ret);
?>