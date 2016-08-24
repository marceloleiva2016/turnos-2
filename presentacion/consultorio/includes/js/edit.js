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

    if(activo==0) {
        document.getElementById('baja').style.display = 'none';
        document.getElementById('horariosForm').style.display = 'none';
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
                        data: "id="+id,
                        type: "POST",
                        dataType: "json",
                        url: "includes/ajaxFunctions/bajaConsultorio.php",
                        success: function(data) {
                            if(data.result) {
                                //NOTIFICACION
                                setTimeout( function() {
                                    // create the notification
                                    var notification = new NotificationFx({
                                        message : '<span class="icon icon-message"></span><p>'+data.message+'</p>',
                                        layout : 'attached',
                                        effect : 'bouncyflip',
                                        type : 'notice'
                                    });
                                    // show the notification
                                    notification.show();
                                }, 1200 );
                                //NOTIFICACION
                                document.getElementById('baja').style.display = 'none';
                                document.getElementById('horariosForm').style.display = 'none';
                            } else {
                                //NOTIFICACION
                                setTimeout( function() {
                                    // create the notification
                                    var notification = new NotificationFx({
                                        message : '<span class="icon icon-message"></span><p>'+data.message+'</p>',
                                        layout : 'attached',
                                        effect : 'bouncyflip',
                                        type : 'notice'
                                    });
                                    // show the notification
                                    notification.show();
                                }, 1200 );
                                //NOTIFICACION
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
                //NOTIFICACION
                // create the notification
                var notification = new NotificationFx({
                    message : '<span class="icon icon-message"></span><p>'+data.message+'</p>',
                    layout : 'attached',
                    effect : 'bouncyflip',
                    type : 'notice'
                });
                // show the notification
                notification.show();
                //NOTIFICACION
                if(data.result) {
                    $("#horarios").load("includes/forms/formTablaHorarios.php",{idconsultorio:id});
                }
            }
        });
    });


});