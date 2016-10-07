<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'laboratorio.class.php';
include_once nspcPacientes . 'pacienteDataBaseLinker.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$tipoDoc = Utils::postIntToPHP($_GET['tipoDoc']);
$nroDoc = Utils::postIntToPHP($_GET['nroDoc']);
//$idHCA = 2;

$pacienteDb = new PacienteDatabaseLinker();
$hisclin = htmlentities( $pacienteDb->historiaClinica($tipoDoc , $nroDoc ));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Historia Clinica</title>

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
	
	<div id="divContenedor" title="UDPs" style="width: 800px; margin: 0 auto 0 auto">
		
		
		<div id="divHistClin" style="clear: both;">
		<?php 
		if(empty($hisclin))
		{
		?>
		<b>No se encuentra historia clinica cargada</b>
		<?php
		} 
		else 
		{
			echo nl2br($hisclin);
		}
		?>
		
		
		
		</div>
		
	</div>
	
	
<script>

	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	$(".numeric").numeric();

</script>

</body>
</html>