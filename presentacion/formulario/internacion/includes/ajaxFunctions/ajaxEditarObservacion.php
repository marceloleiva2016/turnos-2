<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
  echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$idObs = $_REQUEST['idObs'];

$descripcion = $_REQUEST['observacion'];

$dbEpicrisis = new EpicrisisDatabaseLinker();

$data = $dbEpicrisis->editarObservacion($idObs, $descripcion);

if($data->result)
{
  $data->show = true;
  $data->message = "Editado correctamente";
}

echo json_encode($data);
?>