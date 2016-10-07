<?
$FUNCION='M';
$AREA_P='COEXP';
include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//conexion con la base 
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);

   
   
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcCommons . 'utils.php';

session_start();
$baseDeDAtos = new GeneralesDataBaseLinker();

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$firePhp->log($_POST, "POST");

$error = false;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pacientes en UDP</title>

	<link type="text/css" rel="Stylesheet" href="/tools/jquery/css2/jquery-ui-1.8.16.custom.css" />
   <!--  <link type="text/css" rel="Stylesheet" href="/tools/jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" />--> 
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/jqGrid/css/ui.jqgrid.css" /> 
	<style><!--TODO: TODOS ESTOS ESTILOS LOS TENGO QUE SACAR A OTRO ARCHIVO DE ESTILOS-->
	
	</style>
    
    <script src="/tools/jquery/js/jquery-last.min.js" type="text/javascript"></script>
	<script src="/tools/jquery/js/jquery-ui-last.custom.min.js" type="text/javascript"></script>
	  
	<script src="/tools/jquery/jqGrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="/tools/jquery/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    
	<!--<script src="includes/js/resumenMensualInternacion.js" type="text/javascript"></script>-->
	
	<style>
		/*#contenedorPacientes{
		
			filter:alpha(opacity=60);
			-moz-opacity:.6;
			opacity:.6
		}
		#divTabla {
			filter:alpha(opacity=99);
			-moz-opacity:.99;
			opacity:.99
		
		}*/
		a#cerrarContenedorPacientes, 
		a:VISITED#cerrarContenedorPacientes {
			color: #aaa;
			font-weight: bold;
			font-variant: normal;
			font-style: normal;
			text-decoration: none;
			font-size: 13pt;
		}
		a:HOVER#cerrarContenedorPacientes {
			color: #fff;
			font-weight: bold;
			font-variant: normal;
			font-style: normal;
			text-decoration: none;
		}
		
	</style>
</head>


<body bgcolor = "#FFFFFF" style="background-color: #17294B;">
<script>
function detalle(id)
{
	$("#nuevoHCA").val(id);
	$("#frmSeleccionarPaciente").submit();
	
}

$(document).ready(function() {
	//alert($("#frmSeleccionarPaciente").css('height'));
	//$("#toolbar-container").height();
	grid = $("#datosJson");
	
	grid.jqGrid(
		{ 
			url:'includes/ajaxFunctions/jsonPacientesUDP.php?q=2', 
			datatype: "json", 
			colNames:['Cama','Nro. Doc', 'Paciente', 'Ingreso', 'Motivo Consulta','Accion'], 
			colModel:[ 
			 	{name:'cama', index:'cama',width:40, sortable:true, align:"right", fixed:true}, 
			 	{name:'nrodoc',index:'nrodoc', width:70, sortable:true, align:"right", fixed:true}, 
			 	{name:'nombre',index:'nombre', width:140, sortable:true, align:"left", fixed:true},
			 	{name:'ingreso',index:'ingreso', width:120, sortable:true, align:"right", fixed:true},
			 	{name:'motivo',index:'motivo', width:260, sortable:false, align:"left", fixed:true},
			 	{name:'act',index:'act', width:120, sortable:false,align:"center",search:false, fixed:true}
			 	], 
			 rowNum:10, 
			 //rowList:[10,20,30,50], 
			 pager: '#pager2', 
			 sortname: 'ingreso', 
			 viewrecords: true, 
			 sortorder: "asc",
			// footerrow:true,
			//userDataOnFooter : true, 
			altRows : true,
			 	gridComplete: function()
			 	{ 
					var ids = jQuery("#datosJson").jqGrid('getDataIDs'); 
					for(var i=0;i < ids.length;i++)
					{ 
						var cl = ids[i]; 
						be = "<input style='height:22px;width:80px;' type='button' value='Seleccionar' onclick=\"javascript:detalle('"+cl+"');\" />";  
						jQuery("#datosJson").jqGrid('setRowData',ids[i],{act:be}); 
					}
					cantidadPacientes = $("#datosJson").getGridParam('records');
					
					 if(cantidadPacientes < 10)
					 {
						 altura = cantidadPacientes * 23;
					 }
					 else
					 {
						 altura = 230;
					 } 
					 altura += 90; 
					 					 
					$("#toolbar-container").height(altura);				 
					 $("#toolbar-container").slideDown('slow');
					 $("#cerrarContenedorPacientes").click(function(event){
						 event.preventDefault();
						 $("#toolbar-container").slideUp('slow');
					 });					
				},
			 width: 800,
			 height: "100%"
			 //caption:"Pacientes Internados en UDP" 
		}); 
		
		$("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
	
});
</script>

<div id="contenedorPacientes" style="clear: both; text-align: center; width: 100%; height: 100%" >
  	<div id="contenedorPacientesHeader" style="clear: both; text-align: right; width: 98%; height: 20px;">
  		<a href="#" id="cerrarContenedorPacientes" title="Cerrar">X</a>
  	</div>
	<form action="formularioUDP.php" method="post" id="frmSeleccionarPaciente" >
		<div id="divTabla" title="Seleccionar Paciente" style="width: 800px; margin: 0 auto 0 auto">
			
			<table id="datosJson" width="100%"></table>
			<div id="pager2"></div>
			<div id="list2"></div>
			<input type="hidden" name="id" value="" id="nuevoHCA"/>
			
		</div>
	</form>
</div>
</body>
</html>