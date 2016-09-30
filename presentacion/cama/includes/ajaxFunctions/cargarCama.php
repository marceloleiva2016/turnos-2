<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'camaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$db = new CamaDatabaseLinker();

$data = $_POST;

$respuesta = $db->crearCama($data, $usuarioUnset->getId());

echo json_encode($respuesta);
?>