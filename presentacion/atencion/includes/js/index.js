function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].detalle +"</option>");
    }
}

function seleccionadoEspecialidad(combo)
{
    newesp = $('#especialidad').val();

    vaciarComboSubespecialidades();

    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/jsonSubespecialidades.php',
        data:{esp:newesp},
        success: function(json)
        {
            subespecialidades = json.response;
            cargarOptions($('#subespecialidades'),subespecialidades);
        }
    });
}

function vaciarComboSubespecialidades()
{
    $('#subespecialidades option').remove();
}

$(document).ready(function(){

    $('#enviar').click(function(event){
        event.preventDefault();

        if($('#subespecialidades option:selected').val()=="")
        {
            alert("Debe seleccionar una subespecialidad");    
        }
        else
        {
            $('#form').submit();
        }
    });

});