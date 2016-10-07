function ResetScrollPosition(x,y)
{
	//alert(y);
	window.scrollTo(x, y);
}

function SaveScrollPosition()
{
	var scrolly = $("body").scrollTop(); 
	var scrollx = 0;
	
	//alert(scrollx);
	
	$("#idScrollX").val(scrollx);
	$("#idScrollY").val(scrolly);
}

function refreshPagina()
{
	$("#formHCA").submit();
}

/*
 * la pagina de vista debe tener un div llamado divPrincipal 
 * desde el cual se toma el ancho para el dialogo
 * y setear una variable formOk indicando que se cargo bien
 */
function mostrarDialogoNew(paginaVista, paginaFuncion,params)
{
	 $( "#dialog:ui-dialog" ).dialog( "destroy" );
		$("#dialog-message").css('visibility',"visible");
		//$("#dialogForm").load("includes/ajaxFunctions/" + paginaVista,params,function(){
		$.post("includes/ajaxFunctions/" + paginaVista,params,function(data){
			$("#dialogForm").html(data);
			$("#dialogForm" ).dialog({
				modal: true,
				width: $("#divPrincipal").width()+50,
				title:$("#divPrincipal").attr('title'),
				buttons: {
					"Aceptar": function() {
						if(frmOk)
						{
							$.ajax({
				 			 	data: $("#formDatos").serialize(),
				 			 	type: "POST",
				 			 	dataType: "json",
				 			 	url: "includes/ajaxFunctions/"+paginaFuncion,
				 			 	success: function(data){
				 			 		
				 					if(data.result){
				 						if(data.show)
				 						{
				 							alert(data.message);
				 						}
				 							
				 						//location.reload();
				 						refreshPagina();

				 						
				 				 	}
				 				 	else
				 				 	{
				 				 		alert(data.message);
				 				 	}
				 					
				 			 	}
								
				 			});
						}
						$(this).dialog("close");
						//$('#confirmarForm').submit();
					},
					"Cerrar":function() {
						$(this ).dialog("close");
						
					}
				}
			});
		});
}



function mostrarDialogoAux(paginaVista, paginaFuncion,ide)
{
	 $( "#dialog:ui-dialog" ).dialog( "destroy" );
		$("#dialog-message").css('visibility',"visible");
		$("#dialogForm").load("includes/ajaxFunctions/" + paginaVista,{'id':ide},function(){
			$("#dialogForm" ).dialog({
				modal: true,
				width: $("#divPrincipal").width()+50,
				title:$("#divPrincipal").attr('title'),
				buttons: {
					"Aceptar": function() {
							$.ajax({
				 			 	data: $("#formDatos").serialize()+"&id="+ide,
				 			 	type: "POST",
				 			 	dataType: "json",
				 			 	url: "includes/ajaxFunctions/"+paginaFuncion,
				 			 	success: function(data){
				 			 		
				 					if(data.result){
				 						if(data.show)
				 						{
				 							alert(data.message);
				 						}
				 							
				 						//location.reload();
				 						$("#formHCA").submit();
				 						
				 				 	}
				 				 	else
				 				 	{
				 				 		alert(data.message);
				 				 	}
				 					
				 			 	},
				 			 	
				 			 	complete: function(data)
				 			 	{
				 			 		//location.reload();
				 					$("#formHCA").submit();

				 			 	}
							
								
				 			});
						
						$(this).dialog("close");
						//$('#confirmarForm').submit();
					},
					"Cerrar":function() {
						$(this ).dialog("close");
						
					}
				}
			});
		});
}


function mostrarDialogo(paginaVista, paginaFuncion,ide)
{
	 $( "#dialog:ui-dialog" ).dialog( "destroy" );
		$("#dialog-message").css('visibility',"visible");
		$("#dialogForm").load("includes/ajaxFunctions/" + paginaVista,{'id':ide},function(){
			$("#dialogForm" ).dialog({
				modal: true,
				width: $("#divPrincipal").width()+50,
				title:$("#divPrincipal").attr('title'),
				buttons: {
					"Aceptar": function() {
						if(frmOk)
						{
							$.ajax({
				 			 	data: $("#formDatos").serialize()+"&id="+ide,
				 			 	type: "POST",
				 			 	dataType: "json",
				 			 	url: "includes/ajaxFunctions/"+paginaFuncion,
				 			 	success: function(data){
				 			 		
				 					if(data.result){
				 						if(data.show)
				 						{
				 							alert(data.message);
				 						}
				 							
				 						//location.reload();
				 						$("#formHCA").submit();

				 						
				 				 	}
				 				 	else
				 				 	{
				 				 		alert(data.message);
				 				 	}
				 					
				 			 	}
								
				 			});
						}
						$(this).dialog("close");
						//$('#confirmarForm').submit();
					},
					"Cerrar":function() {
						$(this ).dialog("close");
						
					}
				}
			});
		});
}

function BMDiagnosticoEgreso(idm)
{
	$.contextMenu({
        selector: '.context-menu-one-egreso', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m);
           
        },
        items: {
            /*"edit": 
            	{
          	  name: "Editar", 
          	  icon: "edit",
          	 callback: function(key, options) {
         		  idDiag = this.attr('diagId');
         		  
         		  params = "id="+idm+"&diagId="+idDiag;
      			  mostrarDialogoNew("frmEditarEgreso.php", "editarEgreso.php",params);
          	 }
          	},*/
           
            "delete": 
            	{
          	  name: "Eliminar", 
          	  icon: "delete",
          	  callback: function(key, options) {
          		idDiag = this.attr('diagId');
          		
          		params = "id="+idm+"&diagId="+idDiag;
    			  mostrarDialogoNew("frmEliminarEgreso.php", "eliminarEgreso.php",params);
          	  }
          	},
            "sep1": "---------",
            "quit": 
            	{
          	  name: "Salir", 
          	  icon: "quit"
          	}
        }
    });
	
}


function BMObservaciones(idm)
{
	$.contextMenu({
        selector: '.context-menu-one', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            //window.console && console.log(m) || alert(m);
           
        },
        items: {
            "edit": 
            	{
          	  name: "Editar", 
          	  icon: "edit",
          	 callback: function(key, options) {
         		  idObs = this.attr('obsId');
         		  
         		  params = "id="+idm+"&obsId="+idObs;
      			  mostrarDialogoNew("frmEditarObservacion.php", "editarObservacion.php",params);
          	 }
          	},
           
            "delete": 
            	{
          	  name: "Eliminar", 
          	  icon: "delete",
          	  callback: function(key, options) {
          		idObs = this.attr('obsId');
          		
          		params = "id="+idm+"&obsId="+idObs;
    			  mostrarDialogoNew("frmEliminarObservacion.php", "eliminarObservacion.php",params);
          	  }
          	},
            "sep1": "---------",
            "quit": 
            	{
          	  name: "Salir", 
          	  icon: "quit"
          	}
        }
    });
}

$(document).ready(function() {
	
	$("#btnEstudiosAnteriores").click(function(){
		$("#divEstudiosAnteriores").toggle('slow');
		$("#iconoEstudios").toggleClass('ui-icon-circle-triangle-n');
		
	});
	
	 $( "#tabs" ).tabs({
         beforeLoad: function( event, ui ) {
             ui.jqXHR.error(function() {
                 ui.panel.html(
                     "No se pudo leer esta pagina por favor contactese con el sector de informatica. ");
             });
         }
     });
	 
	 $(".tool[title]").qtip({
			position:
			{
				my: 'bottom left',
			        at: 'top left'
			}
		});
	 
	 BMObservaciones(id);
	 
	 BMDiagnosticoEgreso(id);
	 
	 /******************************FUNCIONES***************************************/
	 $("#btnModificarLaboratorios").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmModificarLaboratorios.php", "agregarLaboratorios.php",id);
	 });
	 
	 $("#btnAgregarRayos").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmAgregarRayos.php", "agregarRayos.php",id);
	 });

	 $("#btnAgregarAltaComplejidad").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmAgregarAltaComplejidad.php", "agregarAltaComplejidad.php",id);
	 });
	 
	 $("#btnAgregarPendientes").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmAgregarPendientes.php", "agregarPendiente.php",id);
	 });
	 
	 $("#btnAgregarObservaciones").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmAgregarObservaciones.php", "agregarObservacion.php",id);
	 });
	 
	 $("#btnAgregarInterconsultas").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmAgregarInterconsultas.php", "agregarInterconsulta.php",id);
	 });
	 
	 $("#btnModificarDiagnosticoIngreso").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmModificarDiagnosticoIngreso.php", "modificarDiagnosticoIngreso.php",id);
	 });
	 
	 $("#btnSalirDeUDP").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmSalidaHS.php", "salidaHS.php",id);
	 });
/*	 
	 $("#btnSalirDeHS").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmSalidaHS.php", "salidaHS.php",id);
	 });
*/	 
	 $("#btnSalirDeDAP").click(function(event){
		 event.preventDefault();
		 mostrarDialogo("frmSalidaDAP.php", "salidaDAP.php",id);
	 });

	 $("#btnCambiarUsuario").click(function(event){
		 event.preventDefault();
		 mostrarDialogoAux("frmCambiarUsuario.php", "cambiarUsuario.php",id);
	 });
	 
	 $("#btnImprimir").click(function(event){
		 event.preventDefault();
		 if(!hayEgreso)
		 {
			 alert("Para poder imprimir debe existir un egreso con diagnostico de salida");
		 }
		 else
		 {
			 window.print();
		 }
		 
		 //$("#contenedorGral").jqprint();
	 });
	 
	 
	 $("#btnCmbPaciente").click(function(event){
		 event.preventDefault();
		 $("#toolbar-container").load("includes/ajaxFunctions/grillaPacientesEnUDP");
	 });

	 $("#btnCmbPacienteDAP").click(function(event){
		 event.preventDefault();
		 $("#toolbar-container").load("includes/ajaxFunctions/grillaPacientesEnDAP");
	 });
	 
	 
	/* var lastScroll = document.body.scrollTop;
	 $(document).scroll(function(e){
		 
	      //console.log( document.body.scrollTop);
	        
	 });*/
	 
	 
});