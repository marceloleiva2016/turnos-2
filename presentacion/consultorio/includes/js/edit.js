$(document).ready(function() {

    if(tipo_consultorio==1) {
        //demanda
        document.getElementById('divTipoConsultorio').style.display = 'none';
        document.getElementById('cargarHorarios').style.display = 'none';
    } else {
        //programado
        document.getElementById('divTipoConsultorio').style.display = 'block';
        $("#horarios").load("includes/forms/formTablaHorarios.php",{idconsultorio:id});
        document.getElementById('cargarHorarios').style.display = 'block';
    }

    $("#baja").click(function(event) {
        event.preventDefault(event);
        $("#dialog:ui-dialog").dialog("destroy");
        $("#dialog-message").css('visibility',"visible");
        $("#dialogBaja" ).dialog({
            modal: true,
            width: 300,
            title: "Baja de consultorio Nº"+id,
            buttons: {
                "Aceptar": function(){
                    $.ajax({
                        data: id,
                        type: "POST",
                        dataType: "json",
                        url: "includes/ajaxFunctions/bajaConsultorio.php",
                        success: function(data) {
                            if(data.result) {
                                if(data.show) {
                                    alert(data.message);
                                }
                                //relodeo la tabla
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Cerrar":function() {
                    $(this).dialog("close");
                }
            }
        });

    });

    $("#submitHorario").click(function(event) {
        event.preventDefault(event);
        $.ajax({
            data: $("#horariosForm").serialize()+"&id="+id,
            type: "POST",
            dataType: "json",
            url: "includes/ajaxFunctions/guardarHorario.php",
            success: function(data) {
                alert(data.message);
                if(data.result) {
                    $("#horarios").load("includes/forms/formTablaHorarios.php",{idconsultorio:id});
                }
            }
        });
    });

});