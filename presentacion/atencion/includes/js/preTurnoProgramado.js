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
}

function vaciarProfesionales()
{
    $('#profesional option').remove();
    $('#especialidadAcept').html('');
    $('#subespecialidadAcept').html('');
    $('#profesionalAcept').html('');
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
            url:'includes/ajaxFunctions/jsonSubespecialidadesProgramado.php',
            data:{especialidad:newespecialidad},
            success: function(json)
            {
                vaciarSubespecialidades();
                if(json.ret)
                {
                    cargarOptions($('#subespecialidad'),json.datos);
                    ingresandoSubEsp();
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
                }
            }
        });
    }
}

$(document).ready(function(){

    $('#enviar').click(function(event){
        event.preventDefault();

        if($('#subespecialidad option:selected').val()=="")
        {
            alert("Debe seleccionar una subespecialidad");
        }
        else if($('#profesional option:selected').val()=="")
        {
            alert("Debe seleccionar un profesional");
        }
        else
        {
            $('#form').submit();
        }
    });

});