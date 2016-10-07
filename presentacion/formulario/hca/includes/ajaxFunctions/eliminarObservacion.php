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

if(!isset($_POST['idObs']) || empty($_POST['idObs']))
{
	$data->result = false;
	$data->message = "No se ha seleccionado ninguna observacion a eliminar";
	
}
else 
{
	$idObs = Utils::postIntToPHP(($_POST['idObs']));
	$comentario = ($_POST['comentario']);
}


if($data->result)
{
	$hcaDb = new HcaDatabaseLinker();
	$observacion = $hcaDb->obtenerObservacion($idObs);
	
	try {
		$hcaDb->eliminarObservacion($observacion, $comentario,$user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo editar la observacion: " . $e->getMessage();
	}

	if($data->result)
	{
		$data->show =false; //se puede usar para debug o para indicar exito
		$data->message = "Observacion eliminada correctamente";
	}
}

echo json_encode($data);
?>