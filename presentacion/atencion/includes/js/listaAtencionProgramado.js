$(document).ready(function(){
    $(".listadoPacientesProgramados").load("includes/forms/tableAtencionProgramado.php",{subespecialidad:sub, profesional:prof});
    
    setInterval(loadPacientes , 10000);
});

function loadPacientes(){ 
    $(".listadoPacientesProgramados").load("includes/forms/tableAtencionProgramado.php",{subespecialidad:sub, profesional:prof});
}

function mostrarFormulario(sel){
    $("#id").val(sel);
    $("#frmSeleccionarPaciente").attr('action',"formulario.php");
    $("#frmSeleccionarPaciente").submit();
}

function llamarPaciente(sel){
    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/ajaxLlamarPaciente.php',
        data:{idturno:sel}
    });
}