var idultimollamado = 0;
var cantidadLista = 0;
var cantidadAtendidos = 0;

function loadPacientes(){
    $("#listadoPacientes").load("includes/forms/pacientesLlamados.php",{id: idturnero});
}

function loadPacientesCaducos(){ 
    $("#listadoPacientesCaducos").load("includes/forms/pacientesLlamadosCaducos.php",{id: idturnero});
    /*sonido.play();*/
}

function recargar(){
    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/ajaxTurneroRegistroCambios.php',
        data:{id:idturnero ,idllamado:idultimollamado, cantidad:cantidadLista},
        success: function(json)
        {
            data = json;

            if(data.cantidadAtendidos!=cantidadAtendidos){
                loadPacientesCaducos();
                cantidadAtendidos = data.cantidadAtendidos;
            }

            if(data.response)
            {
                idultimollamado = data.llamado;
                cantidadLista = data.cantidad;
                loadPacientes();
                loadPacientesCaducos();
                ion.sound.play("door_bell");
            }
        },
        error: function ()
        {
            alert("Fallo la conexion!");
        }
    });
}

$(document).ready(function(){

    ion.sound({
        sounds: [
            {name: "door_bell"},
            {name: "bell_ring"}
        ],
        path: "../includes/plug-in/ion.sound-3.0.7/sounds/",
        preload: true,
        volume: 1.0
    });

    $("#listadoPacientes").load("includes/forms/pacientesLlamados.php",{id: idturnero});

    $("#listadoPacientesCaducos").load("includes/forms/pacientesLlamadosCaducos.php",{id: idturnero});
    
    setInterval(recargar , 5000);

});