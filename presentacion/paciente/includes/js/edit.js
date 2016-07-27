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

$(document).ready(function() {

    $( "#guardar" ).click(function(event){
        event.preventDefault();
        $.ajax({
            data: $( "#formPaciente" ).serialize(),
            type: "POST",
            dataType: "json",
            url: "includes/ajaxFunctions/ajaxModificarPaciente.php",
            success: function(data)
            {
                alert(data.message);
                if(data.ret)
                {
                    $('#formPaciente').get(0).reset(); 
                }
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