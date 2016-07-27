$(document).ready(function(){

    $('#enviar').click(function(event){
        event.preventDefault();

        if($('#especialidad').val()=="")
        {
            alert("Debe seleccionar una subespecialidad");    
        }
        else
        {
            $('#form').submit();
        }
    });

});