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
include_once nspcCommons . 'utils.php';

$data->result = true;

if(!isset($_POST['observacion']) || empty($_POST['observacion']))
{
	$data->result = false;
	$data->message = "Se debe cargar un pendiente";
}
else 
{
	$descripcion = ($_POST['observacion']);	
}

$idHCA = Utils::postIntToPHP($_POST['id']);


$hcaDb = new HcaDatabaseLinker();

$pend = new Pendiente($descripcion);

if($data->result)
{
	try {
		$hcaDb->insertarPendiente($idHCA, $pend, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo agregar el pendiente: " . $e->getMessage();
	}
}

if($data->result)
{
	$data->show =false; //se puede usar para debug o para indicar exito
	$data->message = "Pendiente agregado correctamente";
}


echo json_encode($data);
?>