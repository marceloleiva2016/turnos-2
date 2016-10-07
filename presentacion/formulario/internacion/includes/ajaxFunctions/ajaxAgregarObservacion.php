<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
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
  $dbEpicrisis = new DemandaDatabaseLinker();
  
    try
    {
      $dbEpicrisis->insertarObservacion($_REQUEST['id'], $_REQUEST['tipo_observacion'], $descripcion, $usuarioUnset->getId() );    
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