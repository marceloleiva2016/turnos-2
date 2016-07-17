$(document).ready(function(){
    $(".listadoPacientesProgramados").load("includes/forms/tableAtencionProgramado.php",{subespecialidad:sub, profesional:prof});
    
    setInterval(loadPacientes , 8000);
});

function loadPacientes(){ 
    $(".listadoPacientesProgramados").load("includes/forms/tableAtencionProgramado.php",{subespecialidad:sub, profesional:prof});
}

function mostrarFormulario(sel){
    $("#id").val(sel);
    $("#frmSeleccionarPaciente").attr('action',"formulario.php");
    $("#frmSeleccionarPaciente").submit();
}