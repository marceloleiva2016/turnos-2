function mostrarFormulario(sel)
{
    $("#id").val(sel);
    $("#formConsultorio").attr('action',"edit.php?id="+sel);
    $("#formConsultorio").submit();
}