<?php
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$consulDb = new ConsultorioDatabaseLinker();

session_start();

$ret = new StdClass();

$ret->result = true;

if(!isset($_SESSION['usuario']))
{
    $ret->result = false;
    $ret->message = "Usuario con session finalizada. Por favor refresque la pagina";

} else {

    $usuario = $_SESSION['usuario'];

    $data = unserialize($usuario);
    /*fin de agregado usuario*/

    $ret->message = "Consultorio ingresado Correctamente!";

    if(!isset($_REQUEST['tipo_consultorio']) or $_REQUEST['tipo_consultorio']=="") {
        $ret->message = "Debe seleccionar el tipo de consultorio"; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['comienzo']) or $_REQUEST['comienzo']=="") {
        $ret->message = "Defe definir la fecha de comienzo."; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['profesional']) or $_REQUEST['profesional']=="") {
        $ret->message = "Debe seleccionar un profesional"; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['subespecialidad']) or $_REQUEST['subespecialidad']=="") {
        $ret->message = "Debe seleccionar una subespecialidad";
        $ret->result = false;
    } elseif((!isset($_REQUEST['dias_anticipacion']) or $_REQUEST['dias_anticipacion']=="") AND $_REQUEST['tipo_consultorio']=='2') {
        $ret->message = "Completar los dias de anticipacion"; 
        $ret->result = false;
    } elseif((!isset($_REQUEST['feriados']) or $_REQUEST['feriados']=="") AND $_REQUEST['tipo_consultorio']=='2') {
        $ret->message = "Debe completar si se generan en los feriados"; 
        $ret->result = false;
    } elseif( (!isset($_REQUEST['intervalo_minutos']) or $_REQUEST['intervalo_minutos']=="") AND $_REQUEST['tipo_consultorio']=='2') {
        $ret->message = "Debe ingresar cada cuantos minutos se va a dar el consultorio";
        $ret->result = false;
    }

    if($ret->result) {

        $idtipo_consultorio = $_REQUEST['tipo_consultorio'];
        $subespecialidad = $_REQUEST['subespecialidad'];
        $profesional = $_REQUEST['profesional'];
        $fecha_inicio = $_REQUEST['comienzo'];
        $fecha_fi = $_REQUEST['finalizacion'];

        if($idtipo_consultorio=='2') {
            $dias_anticipacion = $_REQUEST['dias_anticipacion'];
            $feriados = $_REQUEST['feriados'];
            $duracion_turno = $_REQUEST['intervalo_minutos'];
        } else {
            $dias_anticipacion = null;
            $feriados = 'false';
            $duracion_turno = null;
        }

        if($fecha_fi=="") {
            $fecha_fin = "0000-00-00";
        } else {
            $fecha_fin = Utils::postDateToSqlDate($fecha_fi);
        }

        $fecha_ini = Utils::postDateToSqlDate($fecha_inicio);

        $existe = $consulDb->existeConsultorio($idtipo_consultorio, $subespecialidad, $profesional);

        if(!$existe) {

            $guardar = $consulDb->setConsultorio($data->getId(), $idtipo_consultorio, $subespecialidad, $profesional, $dias_anticipacion, $duracion_turno, $feriados, $fecha_ini, $fecha_fin);
            
            if($guardar==false) {
                $ret->result = false;
                $ret->message = "Ocurrio un error al guardar el consultorio";
            } else {
                $ret->id = $guardar;
            }
        } else {
            $ret->result = false;
            $ret->message = "El consultorio ya existe";
        }
    }
}

echo json_encode($ret);