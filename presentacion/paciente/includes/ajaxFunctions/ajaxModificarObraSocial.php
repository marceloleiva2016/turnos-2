<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
include_once datos.'utils.php';

$osDB = new ObraSocialDatabaseLinker();

$llegada = $_REQUEST;

$return = new stdClass();

$text = "";

$return->ret = true;

$ingreso = true;

if($llegada['osoc']!=0)
{


    if(!isset($llegada['osoc_fecha_emision']) OR $llegada['osoc_fecha_emision']=="")
    {
        $llegada['osoc_fecha_emision'] = "NULL";
    } else {
        if (!preg_match ("^\\d{1,2}/\\d{2}/\\d{4}^", $llegada['osoc_fecha_emision'])) {
            $text .= " Fecha de emision invalida ";
            $ingreso = false;
        } else  {
            $llegada['osoc_fecha_emision'] = Utils::phpTimestampToSQLDate(Utils::postDateToPHPTimestamp($llegada['osoc_fecha_emision']));
        }
    } 

    if(!isset($llegada['osoc_fecha_vencimiento']) OR $llegada['osoc_fecha_vencimiento']==""){
        $llegada['osoc_fecha_vencimiento'] = "NULL";
    } else {
        if (!preg_match ("^\\d{1,2}/\\d{2}/\\d{4}^", $llegada['osoc_fecha_vencimiento'])) {
            $text .= " Fecha de vencimiento invalida ";
            $ingreso = false;
        } else  {
            $llegada['osoc_fecha_vencimiento'] = Utils::phpTimestampToSQLDate(Utils::postDateToPHPTimestamp($llegada['osoc_fecha_vencimiento']));    
        }
    }

    if(!isset($llegada['osoc_nro_afiliado']) OR $llegada['osoc_nro_afiliado']=="") {
        $llegada['osoc_nro_afiliado'] = "NULL";
    } else {
        $nroAfiliado = $llegada['osoc_nro_afiliado'];
        if(!is_numeric(trim($nroAfiliado)))
        {
            $text .= " Nro de afiliado debe ser numerico ";
            $ingreso = false;
        }    
    }

    if(!isset($llegada['osoc_empresa']) OR $llegada['osoc_empresa']=="") {
        $llegada['osoc_empresa'] = "";
    } 

    if(!isset($llegada['osoc_direccion']) OR $llegada['osoc_direccion']=="") {
        $llegada['osoc_direccion'] = "";
    } 
}
else if(!isset($llegada['osoc']) OR $llegada['osoc']=="")
{
    $return->ret = false;
    $return->message= "Obra Social no seleccionada";
}

if($ingreso)
{
    try
    {
        $ingreso = $osDB->crearFichaOsoc($llegada);
        $return->message = "Obra social del Paciente registrada correctamente";
    }
    catch (Exception $e)
    {
        $return->ret = false;
        $return->message= "Ocurrio un error interno al guardar la obra social del paciente";
    }

    if($return->ret AND $llegada['idAnterior']!="sinobra")
    {
        $osDB->eliminarFichaoOsoc($llegada['idAnterior']);
    }
}
else
{
    $return->ret = false;
    $return->message= "Error en los siguientes campos: ".$text;
}

echo json_encode($return);