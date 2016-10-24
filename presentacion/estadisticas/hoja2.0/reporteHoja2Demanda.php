<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'estadisticaDatabaseLinker.class.php';
include_once datos.'utils.php';
session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$fecha_solicitud = $_REQUEST['fecha'];

$dateNew = Utils::postDateToPHPTimestamp($fecha_solicitud);

$db = new EstadisticaDatabaseLinker();

$ret = $db->turnosAntendidosYporAntenderDemanda($dateNew);

?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Consulta hoja 2</title>
        <meta name="description" content="formulario para atencion en demanda para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />

        <link media="screen" type='text/css' rel='stylesheet' href='../../includes/css/demo.php' >
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/barra.php">
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/iconos.css">
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />
        <link media="screen" type="text/css" rel="stylesheet" href="includes/css/style.php">
        
        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>

       
    </head>
    <body>
        <!-- barra -->
        <div id="barra" >
            <!-- navegar -->
            <div id="barraImage" >
                <span style="font-size: 2em;" class="icon icon-about"></span>
            </div>
            <div id="navegar">
               &nbsp;&nbsp;&nbsp;<a href="../../menu/">Sistema SITU</a>
                &nbsp;&gt;&nbsp;<a href="indexDemanda.php">Seleccion Mes</a>
                &nbsp;&gt;&nbsp;<a href="#">Hoja 2 Demanda</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="../../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->
        <div id="container" class="container">
            <div id="demo"  align="center">
                <br>
                <?php
                echo "Fecha:".$fecha_solicitud;
                ?>
                <br>
                <table align="center" border="1">
                    <tr>
                        <th>Profesional</th>
                        <th>Subespecialidad</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Estado Turno</th>
                    </tr>

                    <?php
                    for ($i=0; $i < count($ret); $i++) { 

                        echo  "<tr><td>".$ret[$i]['profesional']."</td>";
                        echo  "<td>".$ret[$i]['subespecialidad']."</td>";
                        echo  "<td>".$ret[$i]['fecha_creacion']."</td>";
                        echo  "<td>".$ret[$i]['nombre']." ".$ret[$i]['apellido']."</td>";
                        echo  "<td>".$ret[$i]['estado']."</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>