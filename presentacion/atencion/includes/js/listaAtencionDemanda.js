$(document).ready(function(){
    $(".listadoPacientes").load("includes/forms/tableAtencionDemanda.php",{subespecialidad:sub});

    setInterval(loadPacientes , 10000);
});

function loadPacientes(){ 
    $(".listadoPacientes").load("includes/forms/tableAtencionDemanda.php",{subespecialidad:sub});
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