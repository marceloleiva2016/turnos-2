<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Debe Presionar F5 por que su session expiro.";
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
?>
<!DOCTYPE html>
    <script>

        function validar()
        {
            if($('#NombreProf').val()=='')
            {
                alert("Debe ingresar el nombre del profesional.");
                return false;
            }
            else
            {

                if($('#ApeProf').val()=='')
                {
                    alert("Debe ingresar el apellido del profesional.");
                    return false;
                }
                else
                {
                    if($('#MatNac').val()=='' && $('#MatProv').val()=='')
                    {
                        alert("Debe ingresar alguna matricula para el profesional.");
                        return false;
                    }
                    else
                    {
                        if($('#slctusuario').val()=='')
                        {
                            alert("Debe seleccionar un usuario disponible del profesional.");
                            return false;
                        }
                        else
                        {
                            return true;
                        }
                    }
                }
            }
        }

        function cargarOptions(combo, datos)
        {
            for(i=0; i<datos.length; i++)
            {
                combo.append("<option value='"+datos[i].idusuario+"'>"+ datos[i].detalle +"</option>");
            }
        }

        function vaciarComboUsuario()
        {
            $('#slctusuario option').remove();
        }

        $(document).ready(function()
        {

            $('#buscarUsuario').click(function(event){
                event.preventDefault();
                usernew = $('#usuariobusqueda').val();
                vaciarComboUsuario();
                $.ajax({
                    type:'post',
                    dataType:'json',
                    url:'includes/ajaxFunctions/buscarUsuarioProfesional.php',
                    data:{user:usernew},
                    success: function(json)
                    {
                        usuarios = json;
                        cargarOptions($('#slctusuario'),usuarios);
                    }
                });
            });

        });

    </script>
<body>
    <div id="divPrincipal" title="Agregar Profesional" style="width: 200px; text-align: center; margin:0 auto 0 auto">

        <form method="post" name="formProfesional" id="formProfesional" >

            <input type="text" name="NombreProf" id="NombreProf" placeholder="Nombre del profesional" /><br/><br/>
            
            <input type="text" name="ApeProf" id="ApeProf" placeholder="Apellido del profesional" /><br/><br/>

            <input type="text" name="MatNac" id="MatNac" placeholder="Matricula Nacional"/><br/><br/>

            <input type="text" name="MatProv" id="MatProv" placeholder="Matricula Provincial"/><br/><br/>

            <input type="number" name="TelProf" id="TelProf" placeholder="Telefono"/><br/><br/>

            <input type="text" name="MailProf" id="MailProf" placeholder="Email" /><br/><br/>

            <input type="text" name="usuariobusqueda" id="usuariobusqueda" placeholder="Usuario"/>

            <input type="submit" name="buscarUsuario" id="buscarUsuario" value="Buscar Usuario" class="submit-button"/><br/><br/>

            <select name="slctusuario" id="slctusuario">

                <option value="">Seleccione un usuario</option>

            </select>
            <br/><br/>

        </form>

    </div>
</body>
</html>