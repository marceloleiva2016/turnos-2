var cantidadF5 =0;

function detalle(id)
{
	$("#nuevoHCA").val(id);
	$("#frmSeleccionarPaciente").attr('action',"formularioUDP.php");
	$("#frmSeleccionarPaciente").submit();
	
}

function UGCAM(id)
{
	$("#nuevoHCA").val(id);
	$("#frmSeleccionarPaciente").attr('action',"../hospitalVirtual/formularioUGCAM.php");
	$("#frmSeleccionarPaciente").submit();
	
}


$(document).ready(function() {
	
		$("#excelLink").click(function(event){
			if(cantidadF5 >=20000)
				{
				event.preventDefault();
				alert('No se puede exportar a excel con mas de 20 mil datos');
				}
		});
		$("#dialog:ui-dialog").dialog("destroy");
		
		$(".importe").click(function(event){
			event.preventDefault();
		});
		
 		$("#table").jqGrid(
 		{ 
 			url:'includes/ajaxFunctions/jsonPacientesUDPxMes.php',
 			mtype: "POST",
			postData:{ano:ano,mes:mes},
 			datatype: "json", 
 			colNames:['tipoDoc','NroDoc', 'Nombre', 'Ingreso','Motivo','Sector','Destino UDP','Diagnostico','Profesional','Obra Social','IP','Diag. Salida IEH','accion'],
 			colModel:[ 
 			 	{name:'tipodoc', index:'tipodoc',width:20,align:"left",search:false, fixed:true}, 
 			 	{name:'nrodoc',index:'nrodoc', width:55, align:"right", fixed:true},
 			 	{name:'nombre', index:'nombre',width:130,align:"left", fixed:true},
 			 	
 			 	{name:'ingreso', index:'ingreso',width:55,align:"right",fixed:true,searchoptions:{
 			 				dataInit:function(el){
 			 					$(el).datepicker({
 			 						dateFormat:'yy-mm-dd',
 			 						onSelect: function(dateText, inst){ $("#table")[0].triggerToolbar();}
 			 							});
 			 					}
 			 					
 			 				} 
 			 	},
 			 	
 			 	{name:'motivo', index:'motivo',width:130,align:"left", fixed:true},
 			 	
 			 	{name:'cod_secfis', index:'cod_secfis',width:80,align:"left", fixed:true,stype:"select", editoptions:{value:":Todos;1:UDP Clinica Medica;2:UDP Cirugía;3:UDP Traumatología;4:UDP Pediatría;5:UDP ORL Materno;6:UDP Urología;7:UDP Pediatría Clínica;8:UDP Cardiología Agudos;9:UDP Cardiología;11:UDP Dermatologia;12:UDP Shockroom;13:UDP ORL"}},
 			 	
 			 	{name:'destinoUDP', index:'destinoUDP',width:80,align:"left", fixed:true,stype:"select", editoptions:{value:":Todos;6:Alta;7:Derivacion;8:Derivacion interna;9:Internacion;10:Terapia;11:Quirofano;12:Obito;13:Sin Alta UDP"}},
 			 	
 			 	{name:'diagEgreso',index:'diagEgreso', width:130, align:"left", fixed:true},
 			 	{name:'profesional', index:'profesional',width:100,align:"left", fixed:true},
 			 	
 			 	{name:'obraSocial', index:'obraSocial',width:85,align:"left", fixed:true},
 			 	{name:'ip', index:'ip',width:25,align:"center", fixed:true,stype:"select", editoptions:{value:":Todos;1:Si;0:No"}},
                {name:'salidaIEH', index:'salidaIEH',width:85,align:"left",search:false, fixed:true},
 			 	{name:'act',index:'act', width:120, sortable:false,align:"center",search:false, fixed:true}
 			 	], 
 			 rowNum:20, 
 			 rowList:[10,20,30,50], 
 			 pager: '#pagerTable', 
 			 sortname: 'ingreso', 
 			 viewrecords: true, 
 			 sortorder: "desc",
 			 altRows : true,

	 			gridComplete: function()
			 	{ 
					var ids = jQuery("#table").jqGrid('getDataIDs'); 
					for(var i=0;i < ids.length;i++)
					{ 
						var cl = ids[i]; 
						be = "<input style='height:22px;width:60px;' type='button' value='Ver UDP' onclick=\"javascript:detalle('"+cl+"');\" />";
						be = be + "<input style='height:22px;width:60px;' type='button' value='UGCAM' onclick=\"javascript:UGCAM('"+cl+"');\" />";
						jQuery("#table").jqGrid('setRowData',ids[i],{act:be}); 
					} 
				},

 			 width: 1155,
 			 height: "100%",
 			 
 			 caption:"pacientesUDP"  
 		}); 
 		
// 		$("#list2").jqGrid('navGrid','#pagerTable',{edit:false,add:false,del:false,search:false});
 		$("#table").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

});