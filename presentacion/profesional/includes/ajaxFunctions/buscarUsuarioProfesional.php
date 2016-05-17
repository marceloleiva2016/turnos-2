<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'usuarioDatabaseLinker.class.php';

$userdb = new UsuarioDatabaseLinker();

$variable = $_REQUEST;

if(!isset($_REQUEST['user']))
{
    $usuario = NULL;
}
else
{
    $usuario = $_REQUEST['user'];
    $datos = $userdb->buscarUsuario($usuario);
}

echo json_encode($datos);