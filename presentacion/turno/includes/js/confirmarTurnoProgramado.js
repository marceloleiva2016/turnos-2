$(document).ready(function(){

    $("#confirmarTurnoProgramado").button();

    $("#buscarxnum").button();

    $("#buscarxnum").click(function(event){
        event.preventDefault();
        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        if(tipodoc=="")
        {
            alert("Debe ingresar un número de documento");
        }
        else
        {
            $("#miCargando").css("display", "inline");
            $("#fichaPaciente").css("display", "none");
            $("#fichaPaciente").load("includes/forms/confirmarTurno.php",{nroDoc:nrodoc,tipoDoc:tipodoc},function(){
                $("#miCargando").css("display", "none");
                $("#fichaPaciente").css("display", "inline");
                $("#divConfTurno").css("display", "inline");
                $("#botonConfirmar").css("display", "inline");
            });
        }
    });
    
    $("#confirmarTurnoProgramado").click(function(event){
        event.preventDefault();
        var idtur = $("#turnoRadio:checked").val();
        var usuario = $("#idusuario").val();

        if(idtur!=null)
        {
            $.ajax({
                type:'post',
                dataType:'json',
                url:'includes/ajaxFunctions/jsonConfirmarTurno.php',
                data:{idturno:idtur ,idusuario:usuario},
                success: function(json)
                {
                    if (json.ret==true)
                    {
                        $("#divConfTurno").css("display", "none");
                        $("#fichaPaciente").css("display", "none");
                        $("#botonConfirmar").css("display", "none");
                        //NOTIFICACION
                        // create the notification
                        var notification = new NotificationFx({
                            message : '<span class="icon2 icon-message"></span><p>Turno confirmado correctamente!</p>',
                            layout : 'attached',
                            effect : 'bouncyflip',
                            type : 'notice'
                        });
                        // show the notification
                        notification.show();
                        //NOTIFICACION
                    }
                    else
                    {
                        //NOTIFICACION
                        // create the notification
                        var notification = new NotificationFx({
                            message : '<span class="icon2 icon-message"></span><p>Ocurrio un error al confirmar el turno para el paciente!</p>',
                            layout : 'attached',
                            effect : 'bouncyflip',
                            type : 'notice'
                        });
                        // show the notification
                        notification.show();
                        //NOTIFICACION
                    }
                }
            });    
        }
        else
        {
            //NOTIFICACION
            // create the notification
            var notification = new NotificationFx({
                message : '<span class="icon2 icon-message"></span><p>Debe seleccionar un turno para confirmar!</p>',
                layout : 'attached',
                effect : 'bouncyflip',
                type : 'notice'
            });
            // show the notification
            notification.show();
            //NOTIFICACION
        }
    });

});