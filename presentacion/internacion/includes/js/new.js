var pacienteValido = false;

function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].detalle +"</option>");
    }
}

function vaciarCamas()
{
    $('#cama option').remove();
    $('#sectorAcept').html('');
    $('#camaAcept').html('');
} 

function ingresandoSector()
{
    newsector = $('#sector').val();

    vaciarCamas();

    if(newsector != "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonEspecialidad.php',
            data:{sector:newsector},
            success: function(json)
            {
                vaciarCamas();
                if(json.ret)
                {
                    cargarOptions($('#subespecialidad'),json.datos);

                    $('#sectorAcept').html($("#sector :selected").text());
                    $('#camaAcept').html($("#cama :selected").text());
                    $("#sectorDialog").html($("#sector :selected").text());
                    $("#camaDialog").html($("#cama :selected").text());
                }
            }
        });
    }
}

function ingresandoCama()
{
    subespecialidad = $('#cama').val();

    if(subespecialidad != "")
    {
        $('#sectorAcept').html($("#sector :selected").text());
        $("#camaDialog").html($("#cama :selected").text());
    }
}

function setearValoresDialogos(nrodoc, nombre, especialidad, subespecialidad)
{
    $("#nrodocDialog").html(nrodoc);
    $("#nombreDialog").html(nombre);
    $("#sectorDialog").html(especialidad);
    $("#camaDialog").html(subespecialidad);
}

function setearValoresChequeo(nrodoc, nombre, especialidad, subespecialidad)
{
    $("#nombrePaciente").html(nombre);
    $("#nrodocPaciente").html(nrodoc);
    $("#sectorAcept").html(especialidad);
    $("#camaAcept").html(subespecialidad);
}

function resetearVariables()
{
    $("#nrodoc").val('');
    $("#fichaPaciente").html('');
    setearValoresChequeo('', '', '', '');
}

$(document).ready(function(){

    $("#tabs").tabs();

    $("#buscarxnum").button();

    $("#cargarTurnoDemanda").button();

    $("#buscarxnum").click(function(event){
        event.preventDefault();
        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        if(tipodoc=="")
        {
            //NOTIFICACION
            // create the notification
            var notification = new NotificationFx({
                message : '<span class="icon2 icon-message"></span><p>Debe ingresar un número de documento!</p>',
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
            $("#miCargando").css("display", "inline");
            $("#fichaPaciente").css("display", "none");
            $("#fichaPaciente").load("includes/forms/miniFichaPaciente.php",{nroDoc:nrodoc,tipoDoc:tipodoc},function(){
                $("#miCargando").css("display", "none");
                $("#fichaPaciente").css("display", "inline");
            });
        }

        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonObtenerPaciente.php',
            data:{nroDoc:nrodoc,tipoDoc:tipodoc},
            success: function(json)
            {
                pacienteValido = json.ret;
                if(json.ret)
                {
                    setearValoresChequeo(json.nrodoc, json.nombre, $("#especialidad :selected").text(), $("#subespecialidad :selected").text());
                    setearValoresDialogos(json.nrodoc, json.nombre, $("#especialidad :selected").text(), $("#subespecialidad :selected").text());
                }
            }
        });
    });

    $("#cargarInternacion").click(function(event){
        event.preventDefault();

        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        var subesp = $("#subespecialidad").val();
        var usuario = $("#idusuario").val();

        if(pacienteValido)
        {
            if(subesp!=null)
            {
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:'includes/ajaxFunctions/jsonAgregarTurno.php',
                    data:{tipoDoc:tipodoc, nroDoc:nrodoc, subespecialidad:subesp, idusuario:usuario},
                    success: function(json)
                    {
                        if (json.ret==true)
                        {
                            $("#dialog").dialog("open");
                            pacienteValido = false;
                            resetearVariables();
                        }
                        else
                        {
                            //NOTIFICACION
                            // create the notification
                            var notification = new NotificationFx({
                                message : '<span class="icon2 icon-message"></span><p>Ocurrio un error al ingresar el turno para el paciente!</p>',
                                layout : 'attached',
                                effect : 'bouncyflip',
                                type : 'notice'
                            });
                            // show the notification
                            notification.show();
                            //NOTIFICACION
                        };
                    }
                });
            }
            else
            {
                //NOTIFICACION
                // create the notification
                var notification = new NotificationFx({
                    message : '<span class="icon2 icon-message"></span><p>Debe seleccionar una especialidad y subespecilidad para asigar un turno!</p>',
                    layout : 'attached',
                    effect : 'bouncyflip',
                    type : 'notice'
                });
                // show the notification
                notification.show();
                //NOTIFICACION
            };
        }
        else
        {
            //NOTIFICACION
            // create the notification
            var notification = new NotificationFx({
                message : '<span class="icon2 icon-message"></span><p>Debe ingresar el paciente!</p>',
                layout : 'attached',
                effect : 'bouncyflip',
                type : 'notice'
            });
            // show the notification
            notification.show();
            //NOTIFICACION
        };
    });

    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 400,
        buttons:
            [{
                text: "Imprimir",
                click: function(){
                    $('#dialog').jqprint();
                    $(this).dialog("close");
                }
            },{
                text: "Ok",
                click: function(){
                    $(this).dialog("close");
                }
            }]
    });

});