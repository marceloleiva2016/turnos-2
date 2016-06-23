$(document).ready(function(){

    $('#enviar').click(function(event){
        event.preventDefault();

        if($('#subespecialidades option:selected').val()=="")
        {
            alert("Debe seleccionar una subespecialidad");    
        }
        else
        {
            $('#form').submit();
        }
    });

});