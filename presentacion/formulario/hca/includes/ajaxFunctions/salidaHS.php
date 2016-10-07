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
include_once nspcEstudios . 'rayos.class.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcPacientes . 'paciente.class.php';


$data->result = true;
$hcaDb = new HcaDatabaseLinker();
$generales = new GeneralesDataBaseLinker();
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
		$data->message = "Se debe especificar un diagnostico de salida";
	}
	else 
	{
		$diagId = Utils::postIntToPHP($_POST['diagnosticoid']);
	}
}

if($data->result)
{
	$destino = Utils::postIntToPHP($_POST['destino']);
	$salida = new SalidaUDP();
	$salida->destino = $destino;
	$salida->diagnostico = $diagId;
	//TODO: verificar las cosas
	//Necesito el codigo del sector fisico en donde se encuentra el paciente 
	//al momento de cargarle la salida
	$paciente = $hcaDb->paciente($idHCA);
	$secfis = $generales->obtenerSectorFisicoActual($paciente->tipoDoc, $paciente->nroDoc);
	$salida->sectorFisico = $secfis;
	
	if($hcaDb->tuvoSalida($idHCA))
	{
		$salida->fecha= $hcaDb->ultimaHoraSalida($idHCA);
		$salida->sectorFisico = $hcaDb->ultimoSectorFisico($idHCA); 
	}
	else
	{
		$salida->fecha = time();
		
	}
	
	//Necesito el codigo del profesional
	$profes = $generales->codigoProfesional($generales->nombreUsuario($user_id));
	$salida->profesional = $profes;
	
	
	
	
	if ($salida->destino == TipoDestinoUDP::DERIVACIONint) {
		$salida->centro = Utils::postIntToPHP($_POST['centro']);
	} elseif ($salida->destino == TipoDestinoUDP::INTERNACION) {
		$salida->servicio = Utils::postIntToPHP($_POST['servicio']);
	}
	
	try {
		$hcaDb->insertarSalidaUDP($idHCA, $salida, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo registrar la salida de UDP";
	}
	
}

if($data->result)
{
	$data->show =true; //se puede usar para debug o para indicar exito
	$data->message = "La salida ha sido grabada exitosamente";
}

echo json_encode($data);

?>