<?php
//TODO: hay que fijarse bien quien puede cargar laboratorios
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
include_once nspcHca . 'observacion.class.php';
include_once nspcCommons . 'utils.php';

$data->result = true;

$idHCA = Utils::postIntToPHP($_POST['id']);

if(!isset($_POST['idDiag']) || empty($_POST['idDiag']))
{
	$data->result = false;
	$data->message = "No se ha seleccionado salida para eliminar";
	
}
else 
{
	$idDiag = Utils::postIntToPHP(($_POST['idDiag']));
	$comentario = ($_POST['comentario']);
}


if($data->result)
{
	$hcaDb = new HcaDatabaseLinker();
	if($hcaDb->tipoHca($idHCA)==TipoHCA::UDP)
	{
		$salida = $hcaDb->salidaHSUDP($idHCA);
	}
	else 
	{
		$salida = $hcaDb->salidaHSDAP($idHCA);
	}
	
	
	try {
		$hcaDb->eliminarSalida($salida, $comentario, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo eliminar la salida: " . $e->getMessage();
	}

	if($data->result)
	{
		$data->show =false; //se puede usar para debug o para indicar exito
		$data->message = "Salida eliminada correctamente";
	}
}

echo json_encode($data);
?>