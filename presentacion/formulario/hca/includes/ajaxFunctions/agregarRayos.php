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

$data->result = true;

//rayos['.$estudio->id.'][check]
//rayos['.$estudio->id.'][obs]

$estudios = $_POST['rayos'];
$idHCA = Utils::postIntToPHP($_POST['id']);

$hcaDb = new HcaDatabaseLinker();

foreach ($estudios as $id => $valores)
{
	$rx = new Rayo();
	$rx->id = Utils::postIntToPHP($id);
	$rx->valor = true; //Si vino es porque tiene el checkbox seteado
	$rx->observacion = (empty($valores['obs'])?NULL:$valores['obs']);
	try {
		$hcaDb->insertarRx($idHCA, $rx, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "Error intentando cargar Rx: " . $e->getMessage();
		break;
	}	
}

if($data->result)
{
	$data->show =false; //se puede usar para debug o para indicar exito
	$data->message = "Estudios de Rx agregados correctamente";
}

echo json_encode($data);
?>