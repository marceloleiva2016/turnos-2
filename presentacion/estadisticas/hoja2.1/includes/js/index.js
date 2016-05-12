function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].detalle +"</option>");
    }
}

function seleccionadoAnio(combo)
{
    newanio = $('#anio').val();

    vaciarComboMeses();

    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/jsonMeses.php',
        data:{ano:newanio},
        success: function(json)
        {
            meses = json.response;
            cargarOptions($('#mes'),meses);
        }
    });
}

function vaciarComboMeses()
{
    $('#mes option').remove();
}