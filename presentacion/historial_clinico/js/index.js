function seleccionadoFiltro(combo)
{
    newEspecialidad = $('#especialidad').val();

    $("#listaDeAtenciones").load("forms/formAtencionesHistoria.php",{nroDoc:nrodoc,tipoDoc:tipodoc,especialidad:newEspecialidad},function(){
        llenarFormularios();
    });
}