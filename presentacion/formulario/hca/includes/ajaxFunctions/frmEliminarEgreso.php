<?php
include_once '/home/web/namespacesAdress.php';
include '/home/web/desa/comunes/loginweb.php3';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcHca . 'observacion.class.php';
include_once nspcEstudios . 'rayos.class.php';
include_once nspcCommons . 'utils.php';
session_start();

$message = "";

$idHCA = Utils::postIntToPHP($_POST['id']);

$user_id = $PHP_AUTH_USER;//$_SERVER['PHP_AUTH_USER'];

$idSalida = Utils::postIntToPHP($_POST['diagId']);

//$idHCA = 2;
$base = new HcaDatabaseLinker();
//TODO: este deberia obtenerlo de la base no esta copado sacarlo de la pagina no es seguro

if($base->tipoHca($idHCA)==TipoHCA::UDP)
{
	$salida = $base->salidaHSUDP($idHCA);
}
else 
{
	$salida = $base->salidaHSDAP($idHCA);
}

$userSal = $salida->usr;

$puedeEntrar = false;

//usuarios que pueden modificar lo de otros usuarios
$message = "No tiene permiso para eliminar la salida";

if($user_id == 'ramireza2' || $user_id =='jcosta' || $user_id == 'pazm' || $user_id == 'ordonezj' || $user_id == 'jferreyra')
{
	$puedeEntrar = true;
}

#CALCULO DE DOS HORAS PARA QUE EL PROFESIONAL ELIMNAR LA SALIDA#

// Fecha y hora del usuario al momento de apretar en eliminar
$hoy = getdate();
$datetime_ahora = new DateTime();
$datetime_ahora->setTimestamp($hoy['0']);

// Fecha y hora de la salida al momento que fue grabado en la base de datos.
$datetime_salida =  new DateTime();
$datetime_salida->setTimestamp($salida->fecha);

// Calculo la diferencia entre fecha y hora que hay desde la primera salida y el momento que quiere eliminarla
$interval = $datetime_ahora->diff($datetime_salida);

$tieneTiempo = false;

if($interval->y == 0 AND $interval->m == 0 AND $interval->d < 2)
{
	$tieneTiempo = true;
}
else
{
	$message = "Tiene hasta dos horas despues de haber ingresado la salida para eliminarla";
}

if($userSal==$user_id AND $tieneTiempo)
{
	$puedeEntrar = true;
}


/*if($user_id==$userObs)
{
	$puedeEntrar = $puedeEntrar || !$cerrado;
	$message = "No se puede modificar si el hca se encuentra cerrado";
}
else {
	
	if(!$puedeEntrar)
	{
		$message = "No puede modificar un/a $item que no le pertenece";
	}
}
*/

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

//$firePhp->log();

$error = false;


if(!$puedeEntrar)
{
	$error = true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Eliminar Egreso</title>

	<!--  <link type="text/css" rel="Stylesheet" href="/tools/jquery/css2/jquery-ui-1.8.16.custom.css" />-->
	<!--  <link type="text/css" rel="Stylesheet" href="includes/css/table.css" />-->
   <!--  <link type="text/css" rel="Stylesheet" href="/tools/jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" />--> 
	<!-- link type="text/css" rel="Stylesheet" href="/tools/jquery/validity/css/jquery.validity.css" / -->
    
    <!-- link type="text/css" rel="Stylesheet" href="/tools/jquery/tablesorter/css/style.css" /-->
    <!--<link type="text/css" rel="Stylesheet" href="/tools/jquery/jqGrid/css/ui.jqgrid.css" />-->
	<style><!--TODO: TODOS ESTOS ESTILOS LOS TENGO QUE SACAR A OTRO ARCHIVO DE ESTILOS-->
	
	</style>

     <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
	
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php
if(!$error)
{
?>
	<form action="eliminarEgreso.php" id="formDatos">
	<div id="divPrincipal" title="Eliminar Egreso" style="width: 550px; margin: 0 auto 0 auto">
		
		<div id="divObservaciones" style="clear: both;">
		Esta seguro que desea eliminar la salida correspondiente a este paciente con el diagnostico: </br>
		<p><b>
		<?php echo Utils::phpStringToHTML($salida->diagnosticoToString());?>
		</b>		
		</p>
		
		</div>
		<b>Comentario: </b> 
		<input type="text" name="comentario" size="30"></input>
		<input type="hidden" name="idDiag" value="<?php echo $salida->id;?>"/>
		<input type="hidden" name="id" value="<?php echo $idHCA;?>"/>
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
	
	<div id="divPrincipal" title="Error Editando" style="width: 500px; margin: 0 auto 0 auto">
		<div id="ErrorDiv">
			<?php echo $message; ?>
		</div>
	</div>
<?php 	
} 
?>
	
<script>
$(document).ready(function() {
	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	$(".numeric").numeric();

});
</script>

</body>
</html>