function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].descripcion +"</option>");
    }
}

function vaciarProvincias()
{
    $('#provincia option').remove();
    $('#partido option').remove();
    $('#localidad option').remove();
}

function vaciarPartidos()
{
    $('#partido option').remove();
    $('#localidad option').remove();
}

function vaciarLocalidades()
{
    $('#localidad option').remove();
}

function ingresandoPais()
{
    newPais = $('#pais').val();

    if(newPais != "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonProvincias.php',
            data:{idPais:newPais},
            success: function(json)
            {
                vaciarProvincias();
                if(json.ret)
                {
                    cargarOptions($('#provincia'),json.datos);
                    ingresandoProvincia();
                }
            }
        });
    }
}

function ingresandoProvincia()
{
    newPais = $('#pais').val();
    newProvincia = $('#provincia').val();

    if(newProvincia != "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonPartidos.php',
            data:{idPais:newPais, idProvincia:newProvincia},
            success: function(json)
            {
                vaciarPartidos();
                if(json.ret)
                {
                    cargarOptions($('#partido'),json.datos);
                    ingresandoPartido()
                }
            }
        });
    }
}

function ingresandoPartido()
{
    newProvincia = $('#provincia').val();
    newPartido = $('#partido').val();

    if(newPartido != "" && newProvincia!= "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonLocalidades.php',
            data:{idPais:newPais, idProvincia:newProvincia, idPartido:newPartido},
            success: function(json)
            {
                vaciarLocalidades();
                if(json.ret)
                {
                    cargarOptions($('#localidad'),json.datos);
                }
            }
        });
    }
}

function vaciarComboObrasSociales()
{
    $('#osoc option').remove();
}

$(document).ready(function() {

    var tipoDoc = $("#tipodoc").val();
    var nroDoc = $("#nrodoc").val();

    $("#apartadoObraSocial").load("includes/forms/obraSocialActual.php",{tipodoc:tipoDoc, nrodoc:nroDoc});

    $('#guardar').click(function(event){
        event.preventDefault();
        $.ajax({
            data: $( "#formPaciente" ).serialize(),
            type: "POST",
            dataType: "json",
            url: "includes/ajaxFunctions/ajaxModificarPaciente.php",
            success: function(data)
            {
                //NOTIFICACION
                // create the notification
                var notification = new NotificationFx({
                    message : '<span class="icon2 icon-message"></span><p>'+data.message+'</p>',
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

    $( "#fecha_nac" ).datepicker({
        inline: true
    });

    $( "#radioset" ).buttonset();

    $( "#guardar" ).button();

    $( "#accordionPaciente" ).accordion();

});