<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
  //echo "WHOOPSS, No se encontro ningun usuario registrado";
  echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$idObs = $_REQUEST['idObs'];

$descripcion = $_REQUEST['observacion'];

$dbDemanda = new DemandaDatabaseLinker();

$data = $dbDemanda->editarObservacion($idObs, $descripcion);

if($data->result)
{
  $data->show = true;
  $data->message = "Editado correctamente";
}

echo json_encode($data);
?>