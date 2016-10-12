<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcEstudios . 'rayos.class.php';

$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);

$idHCA = Utils::postIntToPHP($_POST['id']);

//$idHCA = 2;
$base = new HcaDatabaseLinker();
$altaComplejidad = $base->altaComplejidadSinHacer($idHCA); 

if(count($altaComplejidad)==0)
{
    $error = true;
    $message = "No hay estudios de rayos que se puedan agregar";
}

if(!$error)
{
    $error = $base->hayEgresoHSUDP($idHCA)||$base->hayEgresoHSDAP($idHCA);
    
    if($error)
    {
        $message = "No se pueden agregar estudios si ya existe la salida";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar Alta Complejidad</title>
    <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php 
if(!$error)//si no hay errores
{ 
?>
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