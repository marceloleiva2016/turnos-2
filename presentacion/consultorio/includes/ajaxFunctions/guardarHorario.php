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

    $ret->message = "Horario ingresado Correctamente!";

    if(!isset($_REQUEST['hd_horas']) or $_REQUEST['hd_horas']=="" ) {
        $ret->message = "Debe completar todos los campos"; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['hd_minutos']) or $_REQUEST['hd_minutos']=="" ) {
        $ret->message = "Debe completar todos los campos"; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['hh_horas']) or $_REQUEST['hh_horas']=="" ) {
        $ret->message = "Debe completar todos los campos"; 
        $ret->result = false;
    } elseif(!isset($_REQUEST['hh_minutos']) or $_REQUEST['hh_minutos']=="" ) {
        $ret->message = "Debe completar todos los campos";
        $ret->result = false;
    }

    if($ret->result) {
        if($_REQUEST['hd_horas']>=$_REQUEST['hh_horas']) {
            $ret->message = "El horario inicial debe ser menor al final";
            $ret->result = false;
        } else {
            $pattern="/^([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])$/";
            $desde = $_REQUEST['hd_horas'].":".$_REQUEST['hd_minutos'];
            $hasta = $_REQUEST['hh_horas'].":".$_REQUEST['hh_minutos'];

            if(!preg_match($pattern,$desde)) {
                $ret->message = "La hora inicial no sigue el patron de horario"; 
                $ret->result = false;
            } else if(!preg_match($pattern,$hasta)) {
                $ret->message = "La hora final no sigue el patron de horario"; 
                $ret->result = false;
            }

            if($ret->result) {
                $superpone = $consulDb->superponeHorario($_REQUEST['dia_semana'], $_REQUEST['id'], $desde, $hasta);
                if(!$superpone){
                    $ingreso = $consulDb->crearHorarioEnConsultorio($_REQUEST['dia_semana'], $_REQUEST['id'], $desde, $hasta, $data->getId());
                } else {
                    $ret->message = "El rango de horario que desea ingresar se superpone con otro horario!."; 
                    $ret->result = false;
                }
            }
        }
    }

    echo json_encode($ret);
}