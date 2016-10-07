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

$sangre = Utils::elementosNoVacios($_POST['sangre']);
$orina = Utils::elementosNoVacios($_POST['orina']);
$idHCA = Utils::postIntToPHP($_POST['id']);


$hcaDb = new HcaDatabaseLinker();

foreach ($sangre as $id => $value)
{
	$lab = new Labortatorio();
	$lab->tipoLaboratorio = TipoLaboratorio::SANGRE;
	$lab->id = Utils::postIntToPHP($id);
	if($hcaDb->esLaboratorioNumerico($id))
	{
		$lab->esNumerico = true;
		$lab->valor = Utils::postFloatToPHP($value);
	}
	else 
	{
		$lab->esNumerico = false;
		$lab->valor = $value;
	}
	try {
		$hcaDb->insertarLaboratorio($idHCA, $lab, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "Error intentando cargar laboratorio: " . $e->getMessage();
		break;
	}	
}

foreach ($orina as $id => $value)
{
	$lab = new Labortatorio();
	$lab->tipoLaboratorio = TipoLaboratorio::ORINA;
	$lab->id = Utils::postIntToPHP($id);
	if($hcaDb->esLaboratorioNumerico($id))
	{
		$lab->esNumerico = true;
		$lab->valor = Utils::postFloatToPHP($value);
	}
	else 
	{	
		$lab->esNumerico = false;
		$lab->valor = $value;
	}
	try {
		$hcaDb->insertarLaboratorio($idHCA, $lab, $user_id);	
	} catch (Exception $e) {
		$data->result = false;
		$data->message = "Error intentando cargar laboratorio: " . $e->getMessage();
		break;
	}	
}

if($data->result)
{
	$data->show =false; //se puede usar para debug o para indicar exito
	$data->message = "Laboratorios agregados correctamente";
}


echo json_encode($data);
?>