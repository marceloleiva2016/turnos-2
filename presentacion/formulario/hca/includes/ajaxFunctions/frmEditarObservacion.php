<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcHca . 'observacion.class.php';
include_once nspcEstudios . 'rayos.class.php';
	
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';

$message = "";
$idHCA = Utils::postIntToPHP($_POST['id']);
$user_id=$PHP_AUTH_USER;

$idObservacion = Utils::postIntToPHP($_POST['obsId']);

//$idHCA = 2;
$base = new HcaDatabaseLinker();
//TODO: este deberia obtenerlo de la base no esta copado sacarlo de la pagina no es seguro
$observacion = $base->obtenerObservacion($idObservacion);

$userObs = $observacion->usuario;

$puedeEntrar = false;



switch ($observacion->tipoObservacion) {
	case TipoObservacion::OBSERVACION:
		$item = "observacion";
		$mayuscula = "Observacion";
	break;
	
	case TipoObservacion::INTERCONSULTA:
		$item = "interconsulta";
		$mayuscula = "Interconsulta";
	break;
	
	
	case TipoObservacion::PENDIENTE:
		$item = "pendiente";
		$mayuscula = "Pendiente";
	break;
	default:
		throw new Exception("El tipo de observacion no es valido", 1411);
	break;
}


//usuarios que pueden modificar lo de otros usuarios
$message = "No tiene permiso para modificar la/el $item";

if($user_id == 'ramireza2' || $user_id =='romeros')
{
	$puedeEntrar = true;
}

$cerrado = $base->hayEgresoHSUDP($idHCA)||$base->hayEgresoHSDAP($idHCA);
if($user_id==$userObs)
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


$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);


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
	<form action="editarConsulta.php" id="formDatos">
	<div id="divPrincipal" title="Editar <?php echo $mayuscula;?>" style="width: 550px; margin: 0 auto 0 auto">
		
		<div id="divObservaciones" style="clear: both;">
			<b><?php echo $mayuscula?>:</b><br/>
			<textarea rows="5" cols="65" name="observacion" id="observacion"><?php echo Utils::phpStringToHTML($observacion->descripcion);?></textarea> <br />
			<a id ="cantidadLetras"> 0 de 240 caracteres</a>
		
		</div>
		<b>Comentario: </b> 
		<input type="text" name="comentario" size="30"></input>
		<input type="hidden" name="idObs" value="<?php echo $observacion->id;?>"/>
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

	$("#cantidadLetras").html($("#observacion").val().length + " de 240 caracteres");
	
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