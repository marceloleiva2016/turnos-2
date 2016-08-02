$(document).ready(function(){

    $("#eliminarTurnoProgramado").button();

    $("#buscarxnum").button();

    $("#buscarxnum").click(function(event){
        event.preventDefault();
        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        if(tipodoc=="")
        {
            alert("Debe ingresar un número de documento");
        }
        else
        {
            $("#miCargando").css("display", "inline");
            $("#fichaPaciente").css("display", "none");
            $("#fichaPaciente").load("includes/forms/eliminarTurno.php",{nroDoc:nrodoc,tipoDoc:tipodoc},function(){
                $("#miCargando").css("display", "none");
                $("#fichaPaciente").css("display", "inline");
                $("#divConfTurno").css("display", "inline");
                $("#botonEliminar").css("display", "inline");
            });
        }
    });
    
    $("#eliminarTurnoProgramado").click(function(event){
        event.preventDefault();

        var idtur = $("#turnoRadio:checked").val();

        if(idtur!=null)
        {
            $("#dialogoEliminar").dialog("open");
        }
        else
        {
            alert("Debe seleccionar un turno para eliminar");
        }
    });
    
    $("#dialogoEliminar").dialog({
        autoOpen: false,
        width: 400,
        buttons:
            [{
                text: "Aceptar",
                click: function(){
                    var idtur = $("#turnoRadio:checked").val();
                    var usuario = $("#idusuario").val();

                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'includes/ajaxFunctions/jsonEliminarTurno.php',
                        data:{idturno:idtur ,idusuario:usuario},
                        success: function(json)
                        {
                            if (json.ret==true)
                            {
                                $("#divConfTurno").css("display", "none");
                                $("#fichaPaciente").css("display", "none");
                                $("#botonEliminar").css("display", "none");
                                alert("Turno eliminado correctamente");
                            }
                            else
                            {
                                alert("Ocurrio un error al eliminar el turno del paciente!");
                            }   
                        }
                    });
                    $(this).dialog("close");
                }
            },{
                text: "Cancelar",
                click: function(){
                    $(this).dialog("close");
                }
            }]
    });
});