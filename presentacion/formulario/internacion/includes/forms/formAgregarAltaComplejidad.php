<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once datos.'utils.php';

$idFormInt = Utils::postIntToPHP($_POST['id']);

$base = new FormInternacionDatabaseLinker();

$altaComplejidad = $base->altaComplejidadSinHacer($idFormInt); 

$error = false;
if(count($altaComplejidad)==0)
{
    $error = true;
    $message = "No hay estudios de rayos que se puedan agregar";
}

?>
<!DOCTYPE html PUBLIC>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Alta Complejidad</title>
    <script type="text/javascript" src="../../includes/plug-in/jnumeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php 
if(!$error)//si no hay errores
{ 
?>
    <script>
        frmOk = true;
    </script>
    <form action="agregarAltaComplejidad.php" id="formDatos">
    <div id="divPrincipal" title="Agregar Alta Complejidad" style="width: 350px; margin: 0 auto 0 auto">
        <div id="divAltaComplejidad">

            <table cellspacing="8px" style="width: 100%">
            <thead>
            <tr>
                <th style="width: 85%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Alta Complejidad</th>
                <th style="width: 15%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
            </tr>
            </thead>
              <tbody>
                <?php
                    /* @var $estudio AltaComplejidad*/
                    foreach ($altaComplejidad as $estudio) {
                        echo '<tr>';
                        echo '<td title="' . $estudio->descripcion . '">';
                            echo "<b>". $estudio->nombre . "</b>";
                        echo "</td>";
                        echo '<td title="' . $estudio->descripcion . '">';
                            echo '<input type="checkbox" name="altacomplejidad['.$estudio->id.']">';
                        echo "</td>";
                        echo "</tr>";   
                    }
                ?>
              </tbody>
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
    
    <div id="divPrincipal" title="Agregar Alta Complejidad" style="width: 500px; margin: 0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>
</body>
</html>