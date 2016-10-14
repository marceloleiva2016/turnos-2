<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacionAltaComplegidad.class.php';
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

$estudios = $_POST['altacomplejidad'];

$idFormTur = Utils::postIntToPHP($_POST['id']);

$dbFormInt = new FormInternacionDatabaseLinker();

foreach ($estudios as $id => $valores)
{
    $altaComplejidad = new AltaComplejidad();
    $altaComplejidad ->id = Utils::postIntToPHP($id);
    $altaComplejidad ->valor = true; //Si vino es porque tiene el checkbox seteado
    try {
        $dbFormInt->insertarAltaComplejidad($idFormTur, $altaComplejidad, $usuarioUnset->getId());    
    } catch (Exception $e) {
        $data->result = false;
        $data->message = "Error intentando cargar Alta Complejidad: " . $e->getMessage();
        break;
    }   
}

if($data->result)
{
    $data->show =false; //se puede usar para debug o para indicar exito
    $data->message = "Estudios de Alta Complejidad agregados correctamente";
}

echo json_encode($data);
?>