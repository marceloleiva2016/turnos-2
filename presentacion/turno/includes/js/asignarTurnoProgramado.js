var pacienteValido = false;

function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].detalle +"</option>");
    }
}

function vaciarSubespecialidades()
{
    $('#subespecialidad option').remove();
    $('#profesional option').remove();
    $('#especialidadAcept').html('');
    $('#subespecialidadAcept').html('');
    $('#profesionalAcept').html('');
    $('#fechaAcept').html('');
    $('#horaAcept').html('');
}

function vaciarProfesionales()
{
    $('#profesional option').remove();
    $('#especialidadAcept').html('');
    $('#subespecialidadAcept').html('');
    $('#profesionalAcept').html('');
    $('#fechaAcept').html('');
    $('#horaAcept').html('');
} 

function ingresandoEsp()
{
    newespecialidad = $('#especialidad').val();

    vaciarSubespecialidades();

    if(newespecialidad != "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonEspecialidadConfirmados.php',
            data:{especialidad:newespecialidad},
            success: function(json)
            {
                vaciarSubespecialidades();
                if(json.ret)
                {
                    cargarOptions($('#subespecialidad'),json.datos);
                    ingresandoSubEsp();

                    $('#especialidadAcept').html($("#especialidad :selected").text());
                    $('#subespecialidadAcept').html($("#subespecialidad :selected").text());
                    $("#profesionalAcept").html($("#profesional :selected").text());
                    $("#especialidadDialog").html($("#especialidad :selected").text());
                    $("#subespecialidadDialog").html($("#subespecialidad :selected").text());
                    $("#profesionalDialog").html($("#profesional :selected").text());
                }
            }
        });
    }
}

function ingresandoSubEsp()
{
    newsubespecialidad = $('#subespecialidad').val();

    if(newsubespecialidad != "")
    {
        $('#subespecialidadAcept').html($("#subespecialidad :selected").text());
        $("#subespecialidadDialog").html($("#subespecialidad :selected").text());
        $("#profesionalAcept").html($("#profesional :selected").text());
        $("#profesionalDialog").html($("#profesional :selected").text());

        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonProfesionalConfirmados.php',
            data:{subespecialidad:newsubespecialidad},
            success: function(json)
            {
                vaciarProfesionales();
                if(json.ret)
                {
                    cargarOptions($('#profesional'),json.datos);

                    $('#especialidadAcept').html($("#especialidad :selected").text());
                    $('#subespecialidadAcept').html($("#subespecialidad :selected").text());
                    $("#especialidadDialog").html($("#especialidad :selected").text());
                    $("#subespecialidadDialog").html($("#subespecialidad :selected").text());
                    $("#profesionalAcept").html($("#profesional :selected").text());
                    $("#profesionalDialog").html($("#profesional :selected").text());

                    var subesp = $("#subespecialidad").val();
                    var prof = $("#profesional").val();

                    cargarFechas(subesp,prof);
                }
            }
        });
    }
}

function ingresandoProf()
{
    $("#profesionalAcept").html($("#profesional :selected").text());
    $("#profesionalDialog").html($("#profesional :selected").text());

    var subesp = $("#subespecialidad").val();
    var prof = $("#profesional").val();

    cargarFechas(subesp,prof);
}

function setearValoresDialogos(nrodoc, nombre, especialidad, subespecialidad, profesional, fecha, hora)
{
    $("#nrodocDialog").html(nrodoc);
    $("#nombreDialog").html(nombre);
    $("#especialidadDialog").html(especialidad);
    $("#subespecialidadDialog").html(subespecialidad);
    $("#profesionalDialog").html(profesional);
    $("#fechaDialog").html(fecha);
    $("#horaDialog").html(hora);
}

function setearValoresChequeo(nrodoc, nombre, especialidad, subespecialidad, profesional, fecha, hora)
{
    $("#nombrePaciente").html(nombre);
    $("#nrodocPaciente").html(nrodoc);
    $("#especialidadAcept").html(especialidad);
    $("#subespecialidadAcept").html(subespecialidad);
    $("#profesionalAcept").html(profesional);
    $("#fechaAcept").html(fecha);
    $("#horaAcept").html(hora);
}

function resetearVariables()
{
    $("#nrodoc").val('');
    $("#fichaPaciente").html('');
    setearValoresChequeo('', '', '', '', '', '', '');
}

function cargarFechas(subesp, prof)
{
    $("#fechasLoading").css("display", "inline");
    $("#fechasCargador").css("display", "none");

    $("#fechasCargador").load("includes/forms/fechasConsultorio.php",{subespecialidad:subesp,profesional:prof}, function(){
        $("#fechasLoading").css("display", "none");
        $("#fechasCargador").css("display", "inline");
    });
}

function verHorarios(fechaSeleccionada, dia)
{
    var subesp = $("#subespecialidad").val();
    var prof = $("#profesional").val();

    $("#dialogHora").load("includes/forms/horariosConsultorio.php",{fecha:fechaSeleccionada, iddia:dia, subespecialidad:subesp, profesional:prof}, function(){
        $("#fechasLoading").css("display", "none");
        $("#dialogHora").css("display", "inline");
        $("#dialogHora").dialog('option', 'title', 'Horarios de Fecha '+fechaSeleccionada);
        $("#dialogHora").dialog("open");
    });
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
                message : '<span class="icon2 icon-message"></span><p>Debe ingresar un número de documento</p>',
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
                    setearValoresChequeo(json.nrodoc, json.nombre, $("#especialidad :selected").text(), $("#subespecialidad :selected").text(),$("#profesional :selected").text(), '', '');
                    setearValoresDialogos(json.nrodoc, json.nombre, $("#especialidad :selected").text(), $("#subespecialidad :selected").text(),$("#profesional :selected").text(), '', '');
                }
            }
        });
    });

    $("#cargarTurnoProgramado").click(function(event){
        event.preventDefault();

        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        var subesp = $("#subespecialidad").val();
        var prof = $("#profesional").val();
        var fech = $('#fechaAcept').html();
        var hor = $('#horaAcept').html();
        var usuario = $("#idusuario").val();

        if(pacienteValido)
        {
            if(subesp!=null)
            {
                if(prof!=null && fech!='' && hor!='')
                {
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'includes/ajaxFunctions/jsonAgregarTurnoConsultorio.php',
                        data:{tipoDoc:tipodoc, nroDoc:nrodoc, subespecialidad:subesp, profesional:prof, fecha:fech ,hora:hor ,idusuario:usuario},
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
                        message : '<span class="icon2 icon-message"></span><p>Debe seleccionar un profesional y las fechas con su horario para asigar un turno!</p>',
                        layout : 'attached',
                        effect : 'bouncyflip',
                        type : 'notice'
                    });
                    // show the notification
                    notification.show();
                    //NOTIFICACION
                }
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

    $("#dialog").dialog({
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

    $("#dialogHora").dialog({
        autoOpen: false,
        width: '70%',
        buttons:
            [{
                text: "Seleccionar",
                click: function(){
                    if($('input[name="horarioRadio"]').is(':checked'))
                    {
                        setearValoresChequeo($("#nrodocDialog").html(), $("#nombreDialog").html(), $("#especialidad :selected").text(), $("#subespecialidad :selected").text(),$("#profesional :selected").text(),$('#fechaSeleccionada').val(),$('#horarioRadio:checked').val());
                        setearValoresDialogos($("#nrodocDialog").html(), $("#nombreDialog").html(), $("#especialidad :selected").text(), $("#subespecialidad :selected").text(),$("#profesional :selected").text(),$('#fechaSeleccionada').val(),$('#horarioRadio:checked').val());
                        $(this).dialog("close");    
                    }
                    else
                    {
                        //NOTIFICACION
                        // create the notification
                        var notification = new NotificationFx({
                            message : '<span class="icon2 icon-message"></span><p>Debe seleccionar al menos un horario!</p>',
                            layout : 'attached',
                            effect : 'bouncyflip',
                            type : 'notice'
                        });
                        // show the notification
                        notification.show();
                        //NOTIFICACION
                    }                    
                }
            }]
    });

});