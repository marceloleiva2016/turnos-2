function refreshPagina()
{
    $("#formInternacion").submit();
}

function mostrarDialogo(paginaVista, paginaFuncion, ide)
{
    $("#dialog:ui-dialog").dialog("destroy");
    $("#dialog-message").css('visibility',"visible");
    $("#dialogForm").load("includes/forms/" + paginaVista,{'id':ide},function(){
        $("#dialogForm" ).dialog({
            autoOpen: true,
            responsive: true,
            modal: true,
            width: $("#divPrincipal").width()+110,
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
                                    $("#formInternacion").submit();
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
            autoOpen: true,
            responsive: true,
            modal: true,
            width: $("#divPrincipal").width()+110,
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
                                if(data.result)
                                {
                                    if(data.show)
                                    {
                                        alert(data.message);
                                    }
                                    $("#formInternacion").submit();
                                }
                                else
                                {
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

$(document).ready(function()
{
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

    $("#agrEgreso").click(function(event){
        event.preventDefault();
        mostrarDialogo("formAgregarEgreso.php", "ajaxAgregarEgreso.php", id);
    });

    $("#agrObservacion").click(function(event){
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

    $("#agrInterconsultas").click(function(event){
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

    $("#agrPendientes").click(function(event){
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

    $("#agrLaboratorios").click(function(event){
        event.preventDefault();
        mostrarDialogo("formAgregarLaboratorios.php", "ajaxAgregarLaboratorios.php",id);
    });
});