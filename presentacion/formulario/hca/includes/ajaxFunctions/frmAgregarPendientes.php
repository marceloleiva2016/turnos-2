<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'laboratorio.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$idHCA = Utils::postIntToPHP($_POST['id']);
//$idHCA = 2;
$base = new HcaDatabaseLinker();


$error = false;
$message = "";
$pendientes = $base->pendientes($idHCA);

if(count($pendientes)>=5)
{
	$error = true;
	$message = "Tiene un limite de 5 pendientes para agregar";
}
if(!$error)
{
	$error = $base->hayEgresoHSUDP($idHCA)||$base->hayEgresoHSDAP($idHCA);
	
	if($error)
	{
		$message = "No se pueden agregar pendientes si ya existe la salida";
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar resultados laboratorio Sangre</title>

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
	<form action="agregarPendientes.php" id="formDatos">
	<div id="divPrincipal" title="Agregar Pendiente" style="width: 550px; margin: 0 auto 0 auto">
		
		
		<div id="divPendientes" style="clear: both;">
		<b>Pendiente:</b><br/>
		<textarea rows="5" cols="65" name="observacion" id="observacion"></textarea>
		<br />
			<a id ="cantidadLetras"> 0 de 240 caracteres</a>
		
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
	
	<div id="divPrincipal" title="Salida UDP" style="width: 500px; margin: 0 auto 0 auto">
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

	$("#observacion").bind('input propertychange', function(){
			if(($("#observacion").val().length) > 240)
			{
				$("#observacion").val($("#observacion").val().substring(0,240));
			}
			
			$("#cantidadLetras").html($("#observacion").val().length + " de 240 caracteres");
	});
});

</script>

</body>
</html>
