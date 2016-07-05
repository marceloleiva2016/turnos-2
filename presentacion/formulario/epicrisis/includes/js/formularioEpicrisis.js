function refreshPagina()
{
	$("#formEpicrisis").submit();
}

function mostrarDialogo(paginaVista, paginaFuncion, ide)
{
    $("#dialog:ui-dialog").dialog("destroy");
	$("#dialog-message").css('visibility',"visible");
	$("#dialogForm").load("includes/forms/" + paginaVista,{'id':ide},function(){
		$("#dialogForm" ).dialog({
			modal: true,
			width: $("#divPrincipal").width()+50,
			title:$("#divPrincipal").attr('title'),
			buttons: {
				"Aceptar": function(){
					if(frmOk){
						$.ajax({
			 			 	data: $("#formDatos").serialize()+"&id="+ide,
			 			 	type: "POST",
			 			 	dataType: "json",
			 			 	url: "includes/ajaxFunctions/"+paginaFuncion,
			 			 	success: function(data){
			 					if(data.result){
			 						if(data.show){
			 							alert(data.message);
			 						}
			 						$("#formEpicrisis").submit();
			 				 	}
			 				 	else{
			 				 		alert(data.message);
			 				 	}	
			 			 	}
			 			});
					}
					$(this).dialog("close");
				},
				"Cerrar":function() {
					$(this ).dialog("close");
				}
			}
		});
	});
}

function mostrarDialogoObservacion(paginaVista, paginaFuncion, ide, observacion)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogForm").load("includes/forms/" + paginaVista,{'id':ide, 'tipo_observacion':observacion},function(){
        $("#dialogForm" ).dialog({
            modal: true,
            width: $("#divPrincipal").width()+50,
            title:$("#divPrincipal").attr('title'),
            buttons: {
                "Aceptar": function(){
                    if(frmOk){
                        $.ajax({
                            data: $("#formDatos").serialize()+"&id="+ide+"&tipo_observacion="+observacion,
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/"+paginaFuncion,
                            success: function(data){
                                if(data.result){
                                    if(data.show){
                                        alert(data.message);
                                    }
                                    $("#formEpicrisis").submit();
                                }
                                else{
                                    alert(data.message);
                                }   
                            }
                        });
                    }
                    $(this).dialog("close");
                },
                "Cerrar":function() {
                    $(this ).dialog("close");
                }
            }
        });
    });
}

function editarObservacion(idObs)
{
    mostrarDialogo("formEditarObservacion.php", "ajaxEditarObservacion.php", idObs);
}

$(document).ready(function(){

    $(".tool[title]").tooltip({
        position:
        {
            my: 'bottom left',
            at: 'top left'
        }
    });

	$("#imprimir").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            window.print();
        }
        else
        {
            alert("Debe tener el egreso para poder imprimir");
        }
	});

    $("#agrEstIngreso").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede modificar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarEstadoIngreso.php", "ajaxAgregarEstadoIngreso.php", id);
        }
    });

	$("#agrAntecedente").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarAntecedente.php", "ajaxAgregarAntecedente.php", id);
        }
	});

    $("#agrExamenCompl").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarExamenComplementario.php", "ajaxAgregarExamenComplementario.php", id);
        }
    });

    $("#agrIntervMenor").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarIntervencionMenor.php", "ajaxAgregarIntervencionMenor.php", id);
        }
    });

    $("#agrProcQuirurgico").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 1);
        }
    });

    $("#agrEvolucionClinica").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 2);
        }
    });

    $("#agrTratamientoMedico").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 3);
        }
    });

    $("#agrInterrecurrencia").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 4);
        }
    });

    $("#agrRescateBacteriologico").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 5);
        }
    });

    $("#agrTratamientoAlta").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 6);
        }
    });

    $("#agrPendientes").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogoObservacion("formAgregarObservacion.php", "ajaxAgregarObservacion.php", id, 7);
        }
    });

    $("#agrDestino").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarDestino.php", "ajaxAgregarDestino.php", id);
        }
    });

    $("#agrMedHab").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarMedicacionHabitual.php", "ajaxAgregarMedicacionHabitual.php", id);
        }
    });

    $("#agrIntervencion").click(function(event){
        event.preventDefault();
        if (tieneEgreso)
        {
            alert("No se puede agregar una vez ingresado el egreso.")
        }
        else
        {
            mostrarDialogo("formAgregarCronicaIntervencion.php", "ajaxAgregarCronicaIntervencion.php", id);
        }
    });

    $("#agrDiagEgr").click(function(event){
        event.preventDefault();
        mostrarDialogo("formAgregarEgresoEpicrisis.php", "ajaxAgregarEgresoEpicrisis.php", id);
    }); 
});