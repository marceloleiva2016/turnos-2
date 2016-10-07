<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>

	body{	
			/*background:url("../images/fondo.png") repeat-x top left #eff3ff;*/
			/*background-color :#eff3ff;*/
			background-color :#E0ECF8;
			font-size: 9pt;
		}
</style>
<title>Indicar Motivo De Ingreso</title>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
	<form action="frmPedirMotivo.php" id="formDatos">
	<div id="divPrincipal" title="Motivo De Ingreso" style="width: 550px; margin: 0 auto 0 auto">
		<div id="divMotivo" style="clear: both;">
			<b>Motivo de Ingreso:</b><br/>
			<textarea rows="10" cols="65" name="inputMotivo" id="inputMotivo"></textarea>
		</div>
		<div id="divTipoHCA" style="clear: both;">
			<b>Origen de la internaci&oacute;n:</b>
			<select name="tipoHCA" id="tipoHCA">
				<option value="">--Seleccione una opci&oacute;n--</option>
				<option value="1">U.D.P.</option>
				<option value="2">D.A.P.</option>
			</select>
		</div>
		
	</div>
	</form>
</body>
</html>