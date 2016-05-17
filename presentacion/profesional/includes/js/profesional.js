function cargarReclamo(formulario)
    {
        $.ajax({
            data: formulario,
            type: "POST",
            dataType: "json",
            url: "../ajaxFunctions/cargarProfesional.php",
            beforeSend:function()
            {
                $('#formProfesional').hide();
                $('#loader').show();
            },
            success: function(data)
            {
                $('#formProfesional').show();
                $('#loader').hide();
                if(data.ret)
                {
                    $('#formProfesional').get(0).reset();
                    alert(data.message);
                }
                else
                {
                    alert(data.message);
                }
            }
        });
    }

function validar()
{
    if($('#NombreProf').val()=='')
    {
        alert("Debe ingresar el nombre del profesional.");
        return false;
    }
    if($('#ApeProf').val()=='')
    {
        alert("Debe ingresar el apellido del profesional.");
        return false;
    }
    if($('#MatNac').val()=='' && $('#MatProv').val()=='')
    {
        alert("Debe ingresar alguna matricula para el profesional.");
        return false;
    }
    
    return true;
}


$(document).ready(function(){

    $('#btnAgregarProfesional').click(function(event){
        event.preventDefault(event);
        var validado = validar();
        if(validado) {
            formulario = $('#formProfesional').serialize();
            cargarReclamo(formulario);
        }
    });

});