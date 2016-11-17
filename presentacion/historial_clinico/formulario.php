<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'formularioReferiDatabaseLinker.class.php';

$id = $_REQUEST['id'];

$idTipoAtencion = $_REQUEST['idTipoAtencion'];

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

$db->delegarFormulario($id, $idTipoAtencion, $data->getId());