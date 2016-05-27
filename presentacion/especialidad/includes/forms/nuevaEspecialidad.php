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
            if($('#nombre').val()=='') {
                alert("Debe ingresar el nombre de la especialidad.");
                return false;
            } else {
                return true;
            }
        }

    </script>
<body>
    <div id="divPrincipal" title="Agregar Especialidad" style="width: 200px; text-align: center; margin:0 auto 0 auto">

        <form method="post" name="formEspecialidad" id="formEspecialidad" >

            <input type="text" name="nombre" id="nombre" placeholder="Nombre de especialidad" /><br/><br/>

        </form>

    </div>
</body>
</html>