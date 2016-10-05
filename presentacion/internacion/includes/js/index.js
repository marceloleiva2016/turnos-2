function mostrarFormulario(sel){
    $("#id").val(sel);
    $("#formSeleccionarInternacion").attr('action',"formulario.php");
    $("#formSeleccionarInternacion").submit();
}

function cargarSector(id) {
    $("#theGrid").load("includes/forms/formInternadosEnSector.php",{idsector:id});
}

$(document).ready(function(){
    $("#theGrid").load("includes/forms/formInternados.php");
});