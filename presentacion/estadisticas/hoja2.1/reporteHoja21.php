<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';
include_once datos.'estadisticaDatabaseLinker.class.php';

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

$anio = $_REQUEST['anio'];

$mes = $_REQUEST['mes'];

$db = new EstadisticaDatabaseLinker();

$datos = $db->especialidadesConSexosyRangos($mes,$anio);
?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Consulta hoja 2.1</title>
        <meta name="description" content="formulario para atencion en demanda para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />

        <link media="screen" type='text/css' rel='stylesheet' href='../../includes/css/demo.php' >
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/barra.php">
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/iconos.css">
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
        
        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    </head>
    <body>
        <!-- barra -->
        <div id="barra" >
            <!-- navegar -->
            <div id="barraImage" >
                <span style="font-size: 2em;" class="icon icon-about"></span>
            </div>
            <div id="navegar">
                &nbsp;&nbsp;&nbsp;<a href="../../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="index.php">Hoja 2.1 Prefiltro</a>&nbsp;&gt;&nbsp;<a href="#">Hoja 2.1</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="../../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->
        <div id="container" class="container" align="center">
            <br>
            <h1>Resumen Mensual de Pacientes Atendidos</h1>
            <br>
            <?php 
            echo Utils::nombreMes($mes)." del ".$anio;
            ?>
            <br>
            <table id="listado" class="tabla2">
                <tr>
                    <th rowspan="2">Especialidad</th>
                    <th rowspan="2">Subespecialidad</th>
                    <th colspan="2">Menor a 1</th>
                    <th colspan="2">De 1 a 4</th>
                    <th colspan="2">De 5 a 9</th>
                    <th colspan="2">De 10 a 14</th>
                    <th colspan="2">De 15 a 19</th>
                    <th colspan="2">De 20 a 34</th>
                    <th colspan="2">De 35 a 49</th>
                    <th colspan="2">De 50 a 64</th>
                    <th colspan="2">Mayor de 65</th>
                    <th rowspan="2">Total</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                </tr>
                <?php
                for ($i=0; $i < count($datos); $i++) { 
                    echo "<tr>";
                    echo "<td>".$datos[$i]['especialidad']."</td>";
                    echo "<td>".$datos[$i]['nombre']."</td>";
                    for ($z=0; $z < count($datos[$i]['lista']); $z++) {
                        echo "<td>".$datos[$i]['lista'][$z]['M']."</td>";
                        echo "<td>".$datos[$i]['lista'][$z]['F']."</td>";
                    }
                    echo "<td>".$datos[$i]['cantidad']."</td>";    
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </body>
</html>