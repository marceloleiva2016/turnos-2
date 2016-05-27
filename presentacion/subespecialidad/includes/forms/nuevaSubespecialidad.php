<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once datos.'especialidadDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

$dbEsp = new EspecialidadDatabaseLinker();

$especialidades = $dbEsp->getEspecialidades();

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
            if($('#detalle').val()=='') {
                alert("Debe ingresar el nombre de la subespecialidad.");
                return false;
            } else {
                return true;
            }
        }

    </script>
<body>
    <div id="divPrincipal" title="Agregar Subespecialidad" style="width: 200px; text-align: center; margin:0 auto 0 auto">

        <form method="post" name="formSubespecialidad" id="formSubespecialidad" >

            <input type="text" name="detalle" id="detalle" placeholder="Detalle Subespecialidad" /><br/><br/>

            Especialidad:
            <select id="idespecialidad" name="idespecialidad">
                <?php
                for ($i=0; $i < count($especialidades->data); $i++) {
                    echo "<option value=".$especialidades->data[$i]->id." >".$especialidades->data[$i]->detalle."</option>";
                }
                ?>
            </select>

        </form>

    </div>
</body>
</html>