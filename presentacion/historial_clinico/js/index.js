function seleccionadoFiltro(combo)
{
    newEspecialidad = $('#especialidad').val();

    $("#listaDeAtenciones").load("forms/formAtencionesHistoria.php",{nroDoc:nrodoc,tipoDoc:tipodoc,especialidad:newEspecialidad},function(){
        llenarFormularios();
    });
}

function mostrarFormulario(sel, tipo)
{
    $("#id").val(sel);
    $("#idTipoAtencion").val(tipo);
    $("#formAtencion").attr('action',"formulario.php");
    $("#formAtencion").submit();
}