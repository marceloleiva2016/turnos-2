function cargarOptions(combo, datos)
{
    for(i=0; i<datos.length; i++)
    {
        combo.append("<option value='"+datos[i].idc+"'>"+ datos[i].ciudad_nombre +"</option>");
    }
}

function vaciarCiudades()
{
    $('#ciudad option').remove();
} 

function ingresandoCP()
{
    newcp = $('#cp').val();

    vaciarCiudades();

    if(newcp != "")
    {
        $.ajax({
            type:'post',
            dataType:'json',
            url:'includes/ajaxFunctions/jsonCP.php',
            data:{cp:newcp},
            success: function(json)
            {
                vaciarCiudades();
                if(json.ret)
                {
                    cargarOptions($('#ciudad'),json.datos);
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

function validarReqeridos()
{

}

function getEdad(dateString)
{
    var from = dateString.split("-");

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

$(document).ready(function() {

    $( "#guardar" ).click(function(event){
        event.preventDefault();
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
                    $('#formPaciente').get(0).reset(); 
                }
            }
        });
    });

    $( "#fecha_nac" ).datepicker({
        inline: true
    });

    $( "#radioset" ).buttonset();

    $( "#tipodoc" ).selectmenu();

    $( "#guardar" ).button();

    $( "#accordionPaciente" ).accordion();

});