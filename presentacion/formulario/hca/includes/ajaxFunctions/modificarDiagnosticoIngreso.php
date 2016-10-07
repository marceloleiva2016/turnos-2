<?php
//TODO: hay que fijarse bien quien puede cargar Rx
$FUNCION='C';
$AREA_P='PISSMA';

include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//header("Cache-Control: private");
//conexion con la base
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   //TODO: control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);
   
include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcCommons . 'utils.php';
require_once nspcInternacion . 'internacionDatabaseLinker.class.php';
include_once nspcDiagnosticos.'diagnosticosDatabaseLinker.class.php';
$dbDiag = new DiagnosticosDatabaseLinker();

$data->result = true;
$hcaDb = new HcaDatabaseLinker();
//Se leen los parametros del post y en caso de faltar alguno indicamos el error


if(!isset($_POST['id']) || empty($_POST['id']))
{
	$data->result = false;
	$data->message = "Se debe especificar el id del HCA";
}
else 
{
	$idHCA = Utils::postIntToPHP($_POST['id']);
}

if($data->result)
{
	if(!isset($_POST['diagnosticoid']) || empty($_POST['diagnosticoid']))
	{
		$data->result = false;
		$data->message = "Se debe especificar un diagnóstico";
	}
	else 
	{
		$diagId = Utils::postIntToPHP($_POST['diagnosticoid']);
	}
}


/*if($data->result)
{
	if(!$dbDiag->existeEnMin($_POST['diagnosticoid']))
	{
		$data->result = false;
		$data->message = "Esta intentando utilizar un diagnostico no valido. Consulte con Estadisticas";
	}
}
*/



if($data->result)
{
	try {
		$idInternacion = $hcaDb->getIdInternacion($idHCA);
		$internacionDBLinker = new InternacionDatabaseLinker();
		$internacionDBLinker->updateDiagosticoIngreso($idInternacion, $diagId, $user_id);
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo modificar el diagnóstico de ingreso (".$e->getMessage().").";
	}
}

if($data->result)
{
	$data->show = true; //se puede usar para debug o para indicar exito
	$data->message = "El diagnóstico de ingreso ha sido modificado exitosamente";
}

echo json_encode($data);