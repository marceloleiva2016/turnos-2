<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'formularioReferiDatabaseLinker.class.php';

$idInternacion = $_REQUEST['id'];

session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$db = new FormularioReferiDatabaseLinker();

$db->delegarFormulario($idInternacion, 3, $data->getId());