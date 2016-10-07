<?php
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
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
//include_once nspcEstudios . 'laboratorio.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$idHCA = Utils::postIntToPHP($_POST['id']);
//$idHCA = 2;
$base = new HcaDatabaseLinker();

//Verifico que el usuario que quiere modificar diagnotico sea un medico
$error = false;
$generales = new GeneralesDataBaseLinker();
$esMedico = $generales->esProfesional($generales->nombreUsuario($user_id));
$error =$error|| !$esMedico;
if($error)
{
	$message = "Debe ser medico para poder modificar un diagnóstico";
}
else 
{
	$error = $base->hayEgresoHSUDP($idHCA) || $base->hayEgresoHSDAP($idHCA);
	if($error)
	{
		$message = "Ya existe una salida de UDP/DAP";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modificar Diagn&oacute;stico de Ingreso</title>
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/autocomplete/css/customStyle.css" />
    
    <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
    <script src="/tools/jquery/autocomplete/js/jquery.ausu-autosuggest.min.js" type="text/javascript"></script> 
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php
if(!$error)
{
?> 
	<form action="agregarInterconsultas.php" id="formDatos">
	<div id="divPrincipal" title="Modificar Diagnostico de Ingreso" style="width: 550px; margin: 0 auto 0 auto;">
		<div id="divIngresoDiagno" style="min-height: 150px;">
			<b>Diagn&oacute;stico de Ingreso:</b>
		   	<div class="ausu-suggest">
		      <input type="text" size="60" value="" name="diagnostico" id="diagnostico" autocomplete="off" />
		      <input type="text" size="5" value="" name="diagnosticoid" id="diagnosticoid" autocomplete="off" style="visibility: hidden;"/>
		      <input type="hidden" id="cod_diagno" name="cod_diagno" value = ""/>
		   	</div>
		   			
		</div>
	</div>
	</form>
<?php
}
else //Si no es medico 
{
?>
	<script>
		frmOk = false;
	</script>
	
	<div id="divPrincipal" title="Modificar Diagnostico de Ingreso" style="width: 500px; margin: 0 auto 0 auto">
		<div id="ErrorDiv">
			<?php echo $message; ?>
		</div>
	</div>
<?php 	
} 
?>
	
<script>
$(document).ready(function() {
	$.fn.autosugguest({
        className: 'ausu-suggest',
        methodType: 'POST',
        rtnIDs: true,
        dataFile: '/tools/ajax/hospital/diagnosticos.php',
        minChars: 3
    });
});
</script>

</body>
</html>