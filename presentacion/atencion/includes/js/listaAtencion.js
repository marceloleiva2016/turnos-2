$(document).ready(function(){
    $(".listadoPacientes").load("includes/forms/tableAtencion.php",{subespecialidad:sub});
    
    setInterval(loadPacientes , 8000);
});

function loadPacientes(){ 
    $(".listadoPacientes").load("includes/forms/tableAtencion.php",{subespecialidad:sub});
}

function mostrarFormulario(sel)
{
    $("#id").val(sel);
    $("#frmSeleccionarPaciente").attr('action',"formulario.php");
    $("#frmSeleccionarPaciente").submit();
}