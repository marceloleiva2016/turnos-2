var tipoConsul = "";

function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].id+"'>"+ datos[i].detalle +"</option>");
    }
}

function seleccionadoEspeci(combo)
{
    newEspecialidad = $('#especialidad').val();

    vaciarComboSubespecialidades();

    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/jsonSubespecialidades.php',
        data:{especialidad:newEspecialidad},
        success: function(json)
        {
            subespecialidades = json.data;
            cargarOptions($('#subespecialidad'),subespecialidades);
        }
    });
}

function vaciarComboSubespecialidades()
{
    $('#subespecialidad option').remove();
}

function seleccionadoTipoConsul(combo)
{
    tipoConsultNew = $('#tipo_consultorio').val();

    if(tipoConsul!=tipoConsultNew)
    {
        if(tipoConsultNew==1)
        {
            //demanda
            document.getElementById('divTipoConsultorio').style.display = 'none';
        }
        else
        {
            //programado
            document.getElementById('divTipoConsultorio').style.display = 'block';
        }
    }

}

$(document).ready(function() {

    document.getElementById('divTipoConsultorio').style.display = 'none';

    $("#guardarConsultorio").click(function(event) {
        event.preventDefault();
        $.ajax({
            data: $("#consultorioForm").serialize(),
            type: "POST",
            dataType: "json",
            url: "includes/ajaxFunctions/guardarConsultorio.php",
            success: function(data) {
                if(data.result) {
                    $('#consultorioForm').get(0).reset();
                    alert(data.message);
                    window.location = "edit.php?id="+data.id;
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $( "#comienzo" ).datepicker({
        inline: true
    });

    $( "#finalizacion" ).datepicker({
        inline: true
    });

});