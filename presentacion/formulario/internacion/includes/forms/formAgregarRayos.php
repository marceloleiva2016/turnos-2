<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once datos.'utils.php';

$idFormInt = Utils::postIntToPHP($_POST['id']);

$base = new FormInternacionDatabaseLinker();

$rayos = $base->rayosSinHacer($idFormInt); 

$error = false;
if(count($rayos)==0)
{
    $error = true;
    $message = "No hay estudios de rayos que se puedan agregar";
}
?>
<!DOCTYPE html PUBLIC>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Rayos</title>
    <script type="text/javascript" src="../../includes/plug-in/jnumeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>
    <script type="text/javascript">
        function toggleObservaciones(item)
        {
            if($('#'+item+'_check').attr('checked')) {
                $('#'+item+'_obs').attr("disabled", false);
            } else {
                $('#'+item+'_obs').attr("disabled", true);
                $('#'+item+'_obs').val('');
            }           
        }
    </script>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php 
if(!$error)//si no hay errores
{ 
?>
    <script>
        frmOk = true;
    </script>
    <form action="agregarRayos.php" id="formDatos">
    <div id="divPrincipal" title="Agregar Rayos" style="width: 550px; margin: 0 auto 0 auto">
        
        
        <div id="divRayos" style="clear: both;">

            <table cellspacing="8px">
            <thead>
            <tr>
                <th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Rx</th>
                <th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
                <th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Observaciones</th>
            </tr>
            </thead>
                <?php
                    /* @var $estudio Rayo*/
                    foreach ($rayos as $estudio) {
                        echo '<tr>';
                        echo '<td title="' . $estudio->descripcion . '" nowrap>';
                            echo "<b>". $estudio->nombre . "</b>";
                        echo "</td>";
                        echo '<td title="' . $estudio->descripcion . '">';
                            echo '<input type="checkbox" name="rayos['.$estudio->id.'][check]" id="rayos_'.$estudio->id.'_check" onclick="javascript:toggleObservaciones(\'rayos_'.$estudio->id.'\')">';
                        echo "</td>";
                        echo '<td title="' . $estudio->descripcion . '">';
                            echo '<input type="text" size="42" name="rayos['.$estudio->id.'][obs]" id="rayos_'.$estudio->id.'_obs" disabled>';
                        echo "</td>";
                        echo "</tr>";   
                    }
                ?>
            </table>
        </div>
    </div>
    </form>
<?php
}
else //si hay errores
{
?>
    <script>
        frmOk = false;
    </script>
    
    <div id="divPrincipal" title="Agregar Rayos" style="width: 500px; margin: 0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>
</body>
</html>