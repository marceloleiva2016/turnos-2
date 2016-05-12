<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    header ("Location: /epicrisis/index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$data = new stdClass();

$data->result = true;

if(!isset($_REQUEST['observacion']) || empty($_REQUEST['observacion']))
{
  $data->result = false;
  $data->message = "Se debe cargar una observacion antes de continuar";
}
else
{
  $descripcion = $_REQUEST['observacion'];
}

if($data->result)
{
  $dbEpicrisis = new EpicrisisDatabaseLinker();
  
    try
    {
      $dbEpicrisis->insertarObservacion($_REQUEST['id'], $_REQUEST['tipo_observacion'], $descripcion, $usuarioUnset->getId());    
    } 
    catch (Exception $e)
    {
      $data->result = false;
      $data->message = "No se pudo agregar la observacion: " . $e->getMessage();
    }
}

if($data->result)
{
    $data->show = false;
    $data->message = "Ingresado correctamente";
}

echo json_encode($data);
?>