$(document).ready(function() {
    $( "#fecha" ).datepicker({
        inline: true,
         dateFormat: "dd-mm-yy"
    });

    $("#enviar").click(function(event){
        event.preventDefault();
        fecha = $("#fecha").val();
        if(fecha!="")
        {
            if(validate_fecha(fecha))
            {
                $("#fechaForm").submit();
            }
            else
            {
               alert("la fecha no tiene el formato valido");
            }
        }
        else
        {
            alert("Ingrese una fecha en el campo");
        }
    });

});

function validate_fecha(fecha)
{
    var patron=new RegExp("^([0-9]{1,2})([-])([0-9]{1,2})([-])(19|20)+([0-9]{2})$");
    if(fecha.search(patron)==0)
    {
        var values=fecha.split("-");
        if(isValidDate(values[0],values[1],values[2]))
        {
            return true;
        }
    }
    return false;
}

function isValidDate(day,month,year)
{
    var dteDate;
 
    // En javascript, el mes empieza en la posicion 0 y termina en la 11 
    //   siendo 0 el mes de enero
    // Por esta razon, tenemos que restar 1 al mes
    month=month-1;
    // Establecemos un objeto Data con los valore recibidos
    // Los parametros son: año, mes, dia, hora, minuto y segundos
    // getDate(); devuelve el dia como un entero entre 1 y 31
    // getDay(); devuelve un num del 0 al 6 indicando siel dia es lunes,
    //   martes, miercoles ...
    // getHours(); Devuelve la hora
    // getMinutes(); Devuelve los minutos
    // getMonth(); devuelve el mes como un numero de 0 a 11
    // getTime(); Devuelve el tiempo transcurrido en milisegundos desde el 1
    //   de enero de 1970 hasta el momento definido en el objeto date
    // setTime(); Establece una fecha pasandole en milisegundos el valor de esta.
    // getYear(); devuelve el año
    // getFullYear(); devuelve el año
    dteDate=new Date(year,month,day);
 
    //Devuelva true o false...
    return ((day==dteDate.getDate()) && (month==dteDate.getMonth()) && (year==dteDate.getFullYear()));
}