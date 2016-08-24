<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'especialidadDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$db = new EspecialidadDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = true;

$data->message = "Especialidad ingresada correctamente!"; 

$datos = $_POST['nombre'];

$respuesta = $db->crearEspecialidad($datos,$usuarioUnset->getId());

if(!$respuesta->ret)
{
    $data->result = false;

    $data->message = "Ocurrio un error al ingresar la especialidad";    
}

echo json_encode($data);