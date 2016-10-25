var pacienteValido = false;

function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].nro_cama +"</option>");
    }
}

function cargarOptions2(combo, datos)
{
  for(i=0; i<datos.length; i++)
  {
    combo.append("<option value='"+datos[i].id+"'>"+ datos[i].descripcion +"</option>");
  }
}

function vaciarComboDiagnostico()
{
  $('#dg_diagnostico option').remove();
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
            url:'includes/ajaxFunctions/jsonCamasLibres.php',
            data:{sector:newsector},
            success: function(json)
            {
                vaciarCamas();
                if(json.ret)
                {
                    cargarOptions($('#cama'),json.datos);

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
    cama = $('#cama').val();

    if(cama != "")
    {
        $('#sectorAcept').html($("#sector :selected").text());
        $("#camaDialog").html($("#cama :selected").text());
    }
}

function setearValoresDialogos(nrodoc, nombre, sector, cama)
{
    $("#nrodocDialog").html(nrodoc);
    $("#nombreDialog").html(nombre);
    $("#sectorDialog").html(sector);
    $("#camaDialog").html(cama);
}

function setearValoresChequeo(nrodoc, nombre, sector, cama)
{
    $("#nombrePaciente").html(nombre);
    $("#nrodocPaciente").html(nrodoc);
    $("#sectorAcept").html(sector);
    $("#camaAcept").html(cama);
}

function resetearVariables()
{
    $("#nrodoc").val('');
    $("#fichaPaciente").html('');
    $("#motivo").html('');
    $("#dg_diagnostico").val('');
    $('#cama option').remove();

    setearValoresChequeo('', '', '', '');
}

$(document).ready(function(){

    $('#diagFiltrar').click(function(event){
        event.preventDefault();
        cod = $('#codBusq').val();
        diag = $('#diagBusq').val();
            vaciarComboDiagnostico();
            $.ajax({
                type:'post',
                dataType:'json',
                url:'includes/ajaxFunctions/jsonDiagnosticos.php',
                data:{nombre:diag,codigo:cod},
                success: function(json)
                {
                    diagnosticos = json;
                    cargarOptions2($('#dg_diagnostico'),diagnosticos);
                }
            });
    });

    $("#tabs").tabs();

    $("#buscarxnum").button();

    $("#cargarInternacion").button();

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
                    setearValoresChequeo(json.nrodoc, json.nombre, $("#sector :selected").text(), $("#cama :selected").text());
                    setearValoresDialogos(json.nrodoc, json.nombre, $("#sector :selected").text(), $("#cama :selected").text());
                }
            }
        });
    });

    $("#cargarInternacion").click(function(event){
        event.preventDefault();

        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        var motivo = $("#motivo").val();
        var diagnostico = $("#dg_diagnostico").val();
        var cam = $("#cama").val();
        var usuario = $("#idusuario").val();

        if(pacienteValido)
        {
            if(cam!=null)
            {
                if(motivo!="" && diagnostico!=null)
                {
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'includes/ajaxFunctions/jsonAgregarInternacion.php',
                        data:{tipoDoc:tipodoc, nroDoc:nrodoc, motivo_ingreso:motivo, diagnostico_ingreso:diagnostico, cama:cam, idusuario:usuario},
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
                                    message : '<span class="icon2 icon-message"></span><p>Ocurrio un error al internar el paciente!</p>',
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
                        message : '<span class="icon2 icon-message"></span><p>Falta completar el motivo o el diagnostico de ingreso!</p>',
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
                    message : '<span class="icon2 icon-message"></span><p>Debe seleccionar un sector y una cama para internar al paciente!</p>',
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