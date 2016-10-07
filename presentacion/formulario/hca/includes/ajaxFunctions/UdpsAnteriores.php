<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'laboratorio.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$idHCA = Utils::postIntToPHP($_GET['id']);
//$idHCA = 2;
$hcaDBLinker = new HcaDatabaseLinker();
$general = new GeneralesDataBaseLinker();

$paciente = $hcaDBLinker->paciente($idHCA);

$tipoDoc = Utils::postIntToPHP($paciente->tipoDoc);
$nroDoc = Utils::postIntToPHP($paciente->nroDoc);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Listado de UDPs anteriores</title>

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
		
		
		<div id="divUDPs" style="clear: both; font-size: 8pt; margin: 0; padding: 0">
			<table id="datosJsonR" width="100%">
			</table>
		</div>
		
	</div>
	<form id="frmUDPs" action="formularioUDP" target="_blank" method="post">
		<input id="inpUDPs" type="hidden" name="id"/>
	</form>
	<div id="UDPsDialog" style="display: none;"></div>
<script>

function observaciones(id)
{
	//alert(id);
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$("#dialog-message").css('visibility',"visible");
	$("#UDPsDialog").load("includes/ajaxFunctions/DialogObservaciones.php",{'id':id},function(){
		$("#UDPsDialog" ).dialog({
			modal: true,
			width: 500,
			//title:$("#divPrincipal").attr('title'),
			buttons: {
				"Aceptar": function() {
					$(this).dialog("close");
					//$('#confirmarForm').submit();
				}
			}
		});
	});
}

function detalle(id, tipoHca)
{
	if(tipoHca!='UDP')
	{
		$("#frmUDPs").attr('action','formularioDAP.php');
	}
	else
	{
		$("#frmUDPs").attr('action','formularioUDP.php');
	}
	$("#inpUDPs").val(id);
	$("#frmUDPs").submit();
	/*$("#nuevoHCA").val(id);
	$("#frmSeleccionarPaciente").submit();*/
	
}

$(document).ready(function() {

	grid = $("#datosJsonR");
	
	grid.jqGrid(
		{ 
			url:'includes/ajaxFunctions/jsonUdps.php?q=2', 
			mtype: "POST",
			datatype: "json", 
			postData:{tipoDoc:<?php echo $tipoDoc;?>, nroDoc:<?php echo $nroDoc;?>},
			colNames:['Tipo Hca','Fecha Ingreso','Motivo Consulta', 'Diagnostico', 'Profesional', 'accion' ], 
			colModel:[ 
				{name:'tipoHca',index:'tipoHca', width:50, sortable:false, align:"center", fixed:true},
			 	{name:'fecha_ingreso', index:'fecha_ingreso',width:100, sortable:false, align:"rigth", fixed:true}, 
			 	{name:'motivo_consulta',index:'motivo_consulta', width:150, sortable:false, align:"left", fixed:true}, 
			 	{name:'diagnostico',index:'diagnostico', width:160, sortable:false, align:"left", fixed:true},
			 	{name:'profesional',index:'profesional', width:150, sortable:false, align:"left", fixed:true},
			 	{name:'act',index:'act', width:150, sortable:false,align:"center",search:false, fixed:true}
			 	], 
			 rowNum:20, 
			 rowList:[10,20,30,50], 
			 pager: '#pager2R', 
			 sortname: 'fecha_ingreso', 
			 viewrecords: true, 
			 sortorder: "asc",
			 //footerrow:true,
			//userDataOnFooter : true, 
			altRows : true,
				gridComplete: function()
			 	{ 
					var ids = jQuery("#datosJsonR").jqGrid('getDataIDs'); 
					for(var i=0;i < ids.length;i++)
					{ 
						var cl = ids[i]; 
						var tipoHca= jQuery("#datosJsonR").jqGrid('getCell', cl, 'tipoHca');
						be = "<input style='height:22px;width:70px;' type='button' value='Ver UDP' onclick=\"javascript:detalle('"+cl+"','"+ tipoHca +"');\" />";
						be += "<input style='height:22px;width:70px;' type='button' value='Obs' onclick=\"javascript:observaciones('"+cl+"');\" />";  
						jQuery("#datosJsonR").jqGrid('setRowData',ids[i],{act:be}); 
					} 
				},
			 width: 800,
			 height: "100%"
			 //caption:"UDPs anteriores" 
		}); 
		
		$("#list2R").jqGrid('navGrid','#pager2R',{edit:false,add:false,del:false,search:false});
});
</script>

</body>
</html>