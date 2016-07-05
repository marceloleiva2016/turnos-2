<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';

//Profesionales
$profDb = new ProfesionalDatabaseLinker();
$Listaprofesionales = json_decode($profDb->getProfesionalesJson(1, 200, array()));
$profesionalesRows = $Listaprofesionales->rows;

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Debe Presionar F5 por que su session expiro.";
    exit();
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
?>
<!DOCTYPE html>
    <script>

        function validar()
        {
            if($('#profesional').val()=='') {
                alert("Debe seleccionar un profesional.");
                return false;
            } else {
                return true;
            }
        }

        $(document).ready(function(){
            $( "#desde_fecha" ).datepicker({
                inline: true
            });

            $( "#hasta_fecha" ).datepicker({
                inline: true
            });
        });
    </script>
<body>
    <div id="divPrincipal" title="Agregar Feriados y Vacaciones" style="text-align: center; margin:0 auto 0 auto">
        <form method="post" name="formFeriado" id="formFeriado" >
            <fieldset>
                <legend>Profesional / Feriado</legend>
                <select name="profesional" id="profesional">
                    <option value="0">DIA FERIADO</option>
                    <?php
                    for ($i=0; $i < count($profesionalesRows); $i++) {
                        echo "<option value=".$profesionalesRows[$i]->cell[0].">".Utils::phpStringToHTML($profesionalesRows[$i]->cell[1])." ".Utils::phpStringToHTML($profesionalesRows[$i]->cell[2])."</option>";
                    }
                    ?>
                </select>
            </fieldset>
            <fieldset>
                <legend>Fecha</legend>
                Desde:<input name="desde_fecha" id="desde_fecha" type="text" style="width:90px;">&nbsp;
                Hasta:<input name="hasta_fecha" id="hasta_fecha" type="text" style="width:90px;">
            </fieldset>
        </form>
    </div>
</body>
</html>