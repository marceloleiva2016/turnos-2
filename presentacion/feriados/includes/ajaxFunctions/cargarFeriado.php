<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'feriadoDatabaseLinker.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$db = new FeriadoDatabaseLinker();

$ret = new stdClass();

$ret->result = true;

$ret->show = false;

if(!isset($_REQUEST['profesional']) or $_REQUEST['profesional']=="") {
    $ret->message = "Debe seleccionar un profesional del listado"; 
    $ret->result = false;
} elseif(!isset($_REQUEST['desde_fecha']) or $_REQUEST['desde_fecha']=="") {
    $ret->message = "Por favor, ingrese desde que Fecha"; 
    $ret->result = false;
} elseif(!isset($_REQUEST['hasta_fecha']) or $_REQUEST['hasta_fecha']=="") {
    $ret->message = "Por favor, ingrese hasta que Fecha"; 
    $ret->result = false;
} 
if($ret->result) {
    $dbProf = new ProfesionalDatabaseLinker();

    $profesional = $dbProf->getProfesional($_POST['profesional']);

    if (!preg_match ("^\\d{1,2}/\\d{2}/\\d{4}^", $_REQUEST['desde_fecha'])) {
        $ret->message = "Debe ingresar una fecha valida!"; 
        $ret->result = false;
    }

    if (!preg_match ("^\\d{1,2}/\\d{2}/\\d{4}^", $_REQUEST['hasta_fecha'])) {
        $ret->message = "Debe ingresar una fecha valida!"; 
        $ret->result = false;
    } 
}

$FechaDesdeArray = explode("/",$_REQUEST['desde_fecha']);
$FechaHastaArray = explode("/",$_REQUEST['hasta_fecha']);

$DD_DES = $FechaDesdeArray[0];
$MM_DES = $FechaDesdeArray[1];
$AA_DES = $FechaDesdeArray[2];

$DD_HAS = $FechaHastaArray[0];
$MM_HAS = $FechaHastaArray[1];
$AA_HAS = $FechaHastaArray[2];

if (mktime(0,0,0,$MM_DES, $DD_DES, $AA_DES)<mktime(0,0,0,date("m"), date("d"), date("Y"))) {
    $ret->message = "Fecha desde menor a hoy"; 
    $ret->result = false;
} elseif (mktime(0,0,0,$MM_HAS, $DD_HAS, $AA_HAS)<mktime(0,0,0,date("m"), date("d"), date("Y"))) {
    $ret->message = "Fecha hasta menor a hoy"; 
    $ret->result = false;
} elseif (mktime(0,0,0,$MM_DES, $DD_DES, $AA_DES)>mktime(0,0,0,$MM_HAS, $DD_HAS, $AA_HAS)) {
    $ret->message = "Fecha desde mayor a fecha hasta"; 
    $ret->result = false;
}

if($ret->result) {

    $profesional = $_POST['profesional'];

    if($profesional=='0') {
        $ret->message = "No se habilitaran turnos por feriado los dias : ";
    } else {
        $ret->message = "Vacacion del profesional agregada correctamente";
    }
    
    $error = false;

    $i=1;

    $FECHA_HAS=date( "Y-m-d", mktime(0,0,0,$MM_DES,$DD_DES-$i,$AA_DES) );
    list($AA, $MM, $DD)=split('[-]', $FECHA_HAS);
    $FECHA_HASTA=date( "Y-m-d", mktime(0,0,0,$MM_HAS,$DD_HAS,$AA_HAS) );
    while ($FECHA_HAS < $FECHA_HASTA) {
        if(!$error) {

            $FECHA_HAS=date( "Y-m-d", mktime(0,0,0,$MM,$DD+$i,$AA) );
            if(!$db->existeFeriado($FECHA_HAS, $profesional)) {
                $error = $db->crearFeriado($FECHA_HAS, $profesional, $usuarioUnset->getId());
                $ret->message.=",".$FECHA_HAS." ";
            }

        } else {
            exit();
        }

       $i++;
    }

    if($error)
    {
        $ret->result = false;

        $ret->show = true;

        $ret->message = "Ocurrio un error al ingresar el feriado";    
    }      
}

echo json_encode($ret);