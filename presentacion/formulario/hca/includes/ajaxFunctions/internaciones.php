<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'laboratorio.class.php';
include_once nspcPacientes . 'pacienteDataBaseLinker.class.php';
include_once nspcInternacion . 'internacionesManager.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$tipoDoc = Utils::postIntToPHP($_GET['tipoDoc']);
$nroDoc = Utils::postIntToPHP($_GET['nroDoc']);

$int = new InternacionesManager();
$int->cargarInternacionesPaciente($tipoDoc, $nroDoc);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internaciones</title>

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
	
	<div id="divContenedor" title="UDPs" style="width: 800px; margin: 0 0 0 0; padding: 0">
		
		
		<div id="divInternaciones" style="clear: both;font-size: 8pt;">
			<table id="datosJsonInterna" width="100%"></table>
		</div>
		
	</div>
	
	
<script>
$(document).ready(function() {

	grid = $("#datosJsonInterna");
	
	grid.jqGrid(
		{ 
			url:'includes/ajaxFunctions/jsonInternaciones.php?q=2', 
			mtype: "POST",
			datatype: "json", 
			postData:{tipoDoc:<?php echo $tipoDoc;?>, nroDoc:<?php echo $nroDoc;?>},
			colNames:['Fecha Ingreso','Centro', 'Servicio', 'Diagnostico', 'Fecha Egreso', 'Diagnostico','Accion'], 
			colModel:[ 
			 	{name:'fecha_ingreso', index:'fecha_ingreso',width:80, sortable:false, align:"rigth", fixed:true}, 
			 	{name:'centro',index:'centro', width:160, sortable:false, align:"left", fixed:true}, 
			 	{name:'servicio',index:'servicio', width:100, sortable:false, align:"left", fixed:true},
			 	{name:'diagnostico',index:'diagnostico', width:150, sortable:false, align:"left", fixed:true},
			 	{name:'fecha_egreso', index:'fecha_egreso',width:80, sortable:false, align:"rigth", fixed:true},
			 	{name:'diagnosticoE',index:'diagnosticoE', width:150, sortable:false, align:"left", fixed:true},
			 	{name:'detalle',index:'detalle', width:70, sortable:false, align:"center", fixed:true}
			 	], 
			 rowNum:20, 
			 rowList:[10,20,30,50], 
			 pager: '#pager2I', 
			 sortname: 'fecha_ingreso', 
			 viewrecords: true, 
			 sortorder: "asc",
			 //footerrow:true,
			//userDataOnFooter : true, 
			altRows : true,/*
			 	gridComplete: function()
			 	{ 
					var ids = jQuery("#datosJson").jqGrid('getDataIDs'); 
					for(var i=0;i < ids.length;i++)
					{ 
						var cl = ids[i]; 
						be = "<input style='height:22px;width:80px;' type='button' value='Detalle' onclick=\"javascript:detalle('"+cl+"');\" />";  
						jQuery("#datosJson").jqGrid('setRowData',ids[i],{act:be}); 
					} 
				},*/
			 width: 830,
			 height: "100%"
			 //caption:"Internaciones Paciente" 
		}); 
		
		$("#list2I").jqGrid('navGrid','#pager2I',{edit:false,add:false,del:false,search:false});
	
		
	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	$(".numeric").numeric();
});
</script>

</body>
</html>