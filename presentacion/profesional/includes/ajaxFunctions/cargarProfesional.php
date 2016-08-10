<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'profesionalDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$db = new ProfesionalDatabaseLinker();

$data = new stdClass();

$data->result = true;

$data->show = false;

$datos = $_REQUEST;

if(!isset($datos['NombreProf']) OR $datos['NombreProf']=="") {
    $data->mensaje = "Ingresar Nombre";
    $data->result = false;
    $data->show = true;
}

if(!isset($datos['ApeProf']) OR $datos['ApeProf']=="") {
    $data->mensaje = "Ingresar Apellido";
    $data->result = false;
    $data->show = true;
}

if(isset($datos['MailProf']) AND $datos['MailProf']!="") {
    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $datos['MailProf'])) {
        $data->mensaje = "Email invalido.";
        $data->result = false;
        $data->show = true;
    }
}

if(isset($datos['TelProf']) AND $datos['TelProf']!="") {
    $tel = $datos['TelProf'];
    if(!is_numeric(trim($tel))) {
        $data->mensaje = "Nro Telefono debe ser numerico.";
        $data->result = false;
        $data->show = true;
    }
}

if(isset($datos['MatNac']) AND $datos['MatNac']!="") {
    $tel = $datos['MatNac'];
    if(!is_numeric(trim($tel))) {
        $data->mensaje = "La Matricula Nacional debe ser numerica";
        $data->result = false;
        $data->show = true;
    }
}

if(isset($datos['MatProv']) AND $datos['MatProv']!="") {
    $tel = $datos['MatProv'];
    if(!is_numeric(trim($tel))) {
        $data->mensaje = "La Matricula Provincial debe ser numerica";
        $data->result = false;
        $data->show = true;
    }
}

if($data->result) {

    $respuesta = $db->crearProfesional($datos);

    if(!$respuesta->ret) {
        $data->mensaje = "Ocurrio un error al ingresar el profesional";    
        $data->result = false;
        $data->show = true;
    }
}

echo json_encode($data);