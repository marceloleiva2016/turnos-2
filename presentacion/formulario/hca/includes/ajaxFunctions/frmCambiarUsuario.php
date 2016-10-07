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



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cambiar Usuario</title>

	<style><!--TODO: TODOS ESTOS ESTILOS LOS TENGO QUE SACAR A OTRO ARCHIVO DE ESTILOS-->
	
	</style>

    <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
	
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
	<form action="agregarObservaciones.php" id="formDatos">
	<div id="divPrincipal" title="Cambiar Usuario" style="width: 550px; margin: 0 auto 0 auto">
		
		
		<div id="divObservaciones" style="clear: both;">
		<b>Nombre Usuario:</b><br/>
		<input type="text" name="usuario" size="40"></input><br/>
		<b>Password:</b><br/>
		<input type="password" name="password" size="40"></input>
		
		</div>
		
	</div>
	</form>
	
<script>
$(document).ready(function() {
	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	$(".numeric").numeric();
});
</script>

</body>
</html>