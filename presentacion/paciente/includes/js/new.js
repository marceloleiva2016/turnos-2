var reenvioOsoc = false;

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

function calcularEdad()
{
    fecha_nac = $('#fecha_nac').val();

    edad = getEdad(fecha_nac);

    $('#edad').val(edad);
}

function getEdad(dateString)
{
    var from = dateString.split("/");

    var birthDate = new Date(from[2],from[1]-1,from[0]);
    
    var today = new Date();

    var age = today.getFullYear() - birthDate.getFullYear();

    var m = today.getMonth() - birthDate.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate()))
    {
        age--;
    }

    return age;
}

function vaciarComboObrasSociales()
{
    $('#osoc option').remove();
}

$(document).ready(function() {

    $( "#guardar" ).click(function(event){
        event.preventDefault();
        var tipodoc = $('#tipodoc').val();
        var nrodoc = $('#nrodoc').val();

        if(!reenvioOsoc)
        {
            $.ajax({
                data: $( "#formPaciente" ).serialize(),
                type: "POST",
                dataType: "json",
                url: "includes/ajaxFunctions/ajaxAgregarPaciente.php",
                success: function(data)
                {
                    alert(data.message);
                    if(data.ret)
                    {
                        $.ajax({
                            data: $( "#formObraSocial" ).serialize()+"&tipodoc="+tipodoc+"&nrodoc="+nrodoc,
                            type: "POST",
                            dataType: "json",
                            url: "includes/ajaxFunctions/ajaxAgregarObraSocial.php",
                            success: function(data)
                            {
                                if(!data.ret)
                                {
                                    alert(data.message);
                                    reenvioOsoc = true;
                                } else {
                                    window.location = "edit.php?tipodoc="+tipodoc+"&nrodoc="+nrodoc;
                                }
                            }
                        });
                    }
                }
            });
        } else {
            $.ajax({
                data: $( "#formObraSocial" ).serialize()+"&tipodoc="+tipodoc+"&nrodoc="+nrodoc,
                type: "POST",
                dataType: "json",
                url: "includes/ajaxFunctions/ajaxAgregarObraSocial.php",
                success: function(data)
                {
                    if(!data.ret)
                    {
                        alert(data.message);
                        reenvioOsoc = true;
                    } else {
                        window.location = "edit.php?tipodoc="+tipodoc+"&nrodoc="+nrodoc;
                    }
                }
            });
        } 
    });

    $('#osocFiltrar').click(function(event){
        event.preventDefault();
        var det = $('#det_busq').val();
        vaciarComboObrasSociales();
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonObrasSociales.php',
            data:{Det:det},
            success: function(json)
            {
                obrasSociales = json;
                cargarOptions($('#osoc'),obrasSociales);
            }
        });
    });


    $( "#fecha_nac" ).datepicker({
        inline: true
    });

    $( "#osoc_fecha_emision" ).datepicker({
        inline: true
    });

    $( "#osoc_fecha_vencimiento" ).datepicker({
        inline: true
    });

    $( "#radioset" ).buttonset();

    $( "#tipodoc" ).selectmenu();

    $( "#guardar" ).button();

    $( "#accordionPaciente" ).accordion();

    $("#fecha_nac").change(function(){
        calcularEdad();
    });

});