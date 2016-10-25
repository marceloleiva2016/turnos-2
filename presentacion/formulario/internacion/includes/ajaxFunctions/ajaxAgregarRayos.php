<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacionRayos.class.php';
include_once negocio.'usuario.class.php';
include_once datos.'utils.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$data = new stdClass();

$data->result = true;

$estudios = $_POST['rayos'];

$idFormInt = Utils::postIntToPHP($_POST['id']);

$dbFormInt = new FormInternacionDatabaseLinker();

foreach ($estudios as $id => $valores)
{
    $rx = new Rayo();
    $rx->id = Utils::postIntToPHP($id);
    $rx->valor = true; //Si vino es porque tiene el checkbox seteado
    $rx->observacion = (empty($valores['obs'])?NULL:$valores['obs']);
    try {
        $dbFormInt->insertarRx($idFormInt, $rx, $usuarioUnset->getId());  
    } catch (Exception $e) {
        $data->result = false;
        $data->message = "Error intentando cargar Rx: " . $e->getMessage();
        break;
    }   
}

if($data->result)
{
    $data->show =false; //se puede usar para debug o para indicar exito
    $data->message = "Estudios de Rx agregados correctamente";
}

echo json_encode($data);
?>