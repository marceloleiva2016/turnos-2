var cantidadF5 =0;

function detalle(id)
{
	$("#nuevoHCA").val(id);
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
 			url:'includes/ajaxFunctions/jsonPacientesDAPxMes.php', 
 			mtype: "POST",
			postData:{ano:ano,mes:mes},
 			datatype: "json", 
 			colNames:['tipoDoc','NroDoc', 'Nombre', 'Ingreso','Motivo','Destino DAP','Diagnostico','Profesional','Obra Social','IP','accion'], 
 			colModel:[ 
 			 	{name:'tipodoc', index:'tipodoc',width:20,align:"left",search:false}, 
 			 	{name:'nrodoc',index:'nrodoc', width:50, align:"right"},
 			 	{name:'nombre', index:'nombre',width:80,align:"left"},
 			 	
 			 	{name:'ingreso', index:'ingreso',width:50,align:"right",searchoptions:{
 			 				dataInit:function(el){
 			 					$(el).datepicker({
 			 						dateFormat:'yy-mm-dd',
 			 						onSelect: function(dateText, inst){ $("#table")[0].triggerToolbar();}
 			 							});
 			 					}
 			 					
 			 				} 
 			 	},
 			 	
 			 	{name:'motivo', index:'motivo',width:100,align:"left"},
 			 	{name:'destinoDAP', index:'destinoDAP',width:60,align:"left",stype:"select", editoptions:{value:":Todos;1:Alta;2:Derivacion;3:Derivacion interna;4:Internacion;5:Obito;6:Sin Alta HS"}},
 			 	
 			 	{name:'diagEgreso',index:'diagEgreso', width:90, align:"left"},
 			 	{name:'profesional', index:'profesional',width:70,align:"left"},
 			 	{name:'obraSocial', index:'obraSocial',width:70,align:"left"},
 			 	{name:'ip', index:'ip',width:25,align:"center", fixed:true,stype:"select", editoptions:{value:":Todos;1:Si;0:No"}},
 			 	{name:'act',index:'act', width:80, sortable:false,align:"center",search:false, fixed:true}
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
						be = "<input style='height:22px;width:80px;' type='button' value='Ver DAP' onclick=\"javascript:detalle('"+cl+"');\" />";  
						jQuery("#table").jqGrid('setRowData',ids[i],{act:be}); 
					} 
				},

 			 width: 1030,
 			 height: "100%",
 			 
 			 caption:"pacientes DAP"
 		}); 
 		
// 		$("#list2").jqGrid('navGrid','#pagerTable',{edit:false,add:false,del:false,search:false});
 		$("#table").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

});