<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'utils.php';

$gen = new PacienteDatabaseLinker();

$llegada = $_REQUEST;

$return = new stdClass();

$text = "";

$return->ret = true;

$ingreso = true;

if(!isset($llegada['tipodoc']) OR  $llegada['tipodoc']==""){          $text .= "Tipo Documento, ";   $ingreso = false; }
if(!isset($llegada['nrodoc']) OR $llegada['nrodoc']==""){           $text .= "Numero Documento, ";    $ingreso = false; }
if(!isset($llegada['nombre']) OR $llegada['nombre']==""){           $text .= "Nombre, ";    $ingreso = false; }
if(!isset($llegada['apellido']) OR $llegada['apellido']==""){         $text .= "Apellido, ";  $ingreso = false; }
if(!isset($llegada['sexo']) OR $llegada['sexo']==""){             $text .= "Sexo, ";  $ingreso = false; }
if(!isset($llegada['edad']) OR $llegada['edad']==""){             $text .= "Edad, ";  $ingreso = false; }
if(!isset($llegada['pais']) OR $llegada['pais']==""){             $text .= "Pais, ";  $ingreso = false; }
if(!isset($llegada['provincia']) OR $llegada['provincia']==""){             $text .= "Provincia, ";  $ingreso = false; }
if(!isset($llegada['partido']) OR $llegada['partido']==""){             $text .= "Partido, ";  $ingreso = false; }
if(!isset($llegada['localidad']) OR $llegada['localidad']==""){             $text .= "Localidad, ";  $ingreso = false; }
if(!isset($llegada['calle_nombre']) OR $llegada['calle_nombre']==""){     $text .= "Nombre de calle, ";  $ingreso = false; }
if(!isset($llegada['calle_numero']) OR $llegada['calle_numero']==""){     $text .= "Numero de calle, ";  $ingreso = false; }
if(!isset($llegada['donante']) OR $llegada['donante']==""){          $text .= "Es Donante? , ";   $ingreso = false; }
if(!isset($llegada['fecha_nac']) OR $llegada['fecha_nac']==""){        $text .= "Fecha de nacimiento "; $ingreso = false; }
else{
    if (!preg_match ("^\\d{1,2}/\\d{2}/\\d{4}^", $llegada['fecha_nac'])) {
        $text .= "Fecha de nacimiento valida,"; 
        $ingreso = false;
    } else  {
        $llegada['fecha_nac'] = Utils::phpTimestampToSQLDate(Utils::postDateToPHPTimestamp($llegada['fecha_nac']));    
    }
}

if($ingreso)
{
    try
    {
        $ingreso = $gen->crearPaciente($llegada);
        $return->message = "Paciente ingresado correctamente";
    }
    catch (Exception $e)
    {
        $return->ret = false;
        $return->message= "Ocurrio un error interno al ingresar el paciente";
    }
}
else
{
    $return->ret = false;
    $return->message= "Al parecer faltan completar los campos: ".$text;
}

echo json_encode($return);