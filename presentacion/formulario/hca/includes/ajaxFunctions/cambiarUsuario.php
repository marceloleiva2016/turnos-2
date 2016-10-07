<?php
$FUNCION='M';
$AREA_P='COEXP';

include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//header("Cache-Control: private");
//conexion con la base
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);

   
include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcCommons . 'utils.php';

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$generales = new GeneralesDataBaseLinker();
$cheq = $generales->verificarUsuarioContrasenia($usuario, $password);
$data->result = true;
$direccion = $_SERVER['SERVER_NAME'];
if($cheq)
{
	$refer = $_SERVER['HTTP_REFERER'];
	Header ("Location: http://$usuario:$password@$direccion/produ/hca/formularioHCA.php");
}
else 
{
	$data->result = false;
	$data->message = "Usuario o contraseña incorrecto";	
}

/*

$idHCA = Utils::postIntToPHP($_POST['id']);
if(!isset($_POST['observacion']) || empty($_POST['observacion']))
{
	$data->result = false;
	$data->message = "Se debe cargar una observacion";
}
else 
{
	$descripcion = ($_POST['observacion']);	
}





$hcaDb = new HcaDatabaseLinker();

$obs = new Observacion($descripcion);
if($data->result)
{
	try {
		$hcaDb->insertarObservacion($idHCA, $obs, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "No se pudo agregar la observacion: " . $e->getMessage();
	}
}
if($data->result)
{
	$data->show =false; //se puede usar para debug o para indicar exito
	$data->message = "Observacion agregada correctamente";
}
*/
$data->show =false;
echo json_encode($data);
?>