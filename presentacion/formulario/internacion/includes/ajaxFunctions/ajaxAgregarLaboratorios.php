<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacionLaboratorio.class.php';
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

if(!isset($_POST['orina'])){
    $orina = array();
} else {
    $orina = Utils::elementosNoVacios($_POST['orina']);    
}

if(!isset($_POST['sangre'])){
    $sangre = array();
} else {
    $sangre = Utils::elementosNoVacios($_POST['sangre']);
}

$idFormInt = Utils::postIntToPHP($_POST['id']);

$dbFormInt = new FormInternacionDatabaseLinker();

foreach ($sangre as $id => $value)
{
    $lab = new Labortatorio();
    $lab->tipoLaboratorio = TipoLaboratorio::SANGRE;
    $lab->id = Utils::postIntToPHP($id);
    if($dbFormInt->esLaboratorioNumerico($id))
    {
        $lab->esNumerico = true;
        $lab->valor = Utils::postFloatToPHP($value);
    }
    else 
    {
        $lab->esNumerico = false;
        $lab->valor = $value;
    }
    try {
        $dbFormInt->insertarLaboratorio($idFormInt, $lab, $usuarioUnset->getId());
    } catch (Exception $e) {
        $data->result = false;
        $data->message = "Error intentando cargar laboratorio: " . $e->getMessage();
        break;
    }   
}

foreach ($orina as $id => $value)
{
    $lab = new Labortatorio();
    $lab->tipoLaboratorio = TipoLaboratorio::ORINA;
    $lab->id = Utils::postIntToPHP($id);
    if($dbFormInt->esLaboratorioNumerico($id))
    {
        $lab->esNumerico = true;
        $lab->valor = Utils::postFloatToPHP($value);
    }
    else 
    {   
        $lab->esNumerico = false;
        $lab->valor = $value;
    }
    try {
        $dbFormInt->insertarLaboratorio($idFormInt, $lab, $usuarioUnset->getId());
    } catch (Exception $e) {
        $data->result = false;
        $data->message = "Error intentando cargar laboratorio: " . $e->getMessage();
        break;
    }   
}

if($data->result)
{
    $data->show =false; //se puede usar para debug o para indicar exito
    $data->message = "Laboratorios agregados correctamente";
}

echo json_encode($data);
?>