$(document).ready(function(){

    $("#buscar").button();

    $("#buscar").click(function(event){
        event.preventDefault();
        var tipodoc = $("#tipodoc").val();
        var nrodoc = $("#nrodoc").val();
        var nombre = $("#nombre").val();
        if(nrodoc=="")
        {
            if(nombre=="")
            {
                alert("Debe ingresar algun dato para realizar la busqueda");    
            }
            else 
            {
                $("#miCargando").css("display", "inline");
                $("#fichaPaciente").css("display", "none");
                $("#fichaPaciente").load("includes/forms/tablaPacientesXNombre.php",{nombre:nombre},function(){
                    $("#miCargando").css("display", "none");
                    $("#fichaPaciente").css("display", "inline");
                });
            }
        }
        else
        {
            $("#miCargando").css("display", "inline");
            $("#fichaPaciente").css("display", "none");
            $("#fichaPaciente").load("includes/forms/tablaPacientesXNum.php",{nroDoc:nrodoc,tipoDoc:tipodoc},function(){
                $("#miCargando").css("display", "none");
                $("#fichaPaciente").css("display", "inline");
            });
        }
    });
});