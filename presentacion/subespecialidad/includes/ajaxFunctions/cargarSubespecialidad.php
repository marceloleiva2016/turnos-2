<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$db = new SubespecialidadDatabaseLinker();

$data = $_POST;

$respuesta = $db->crearSubespecialidad($data,$usuarioUnset->getId());

echo json_encode($respuesta);

?>