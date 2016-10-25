<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
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
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/login.php">
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
        <link media="screen" type="text/css" rel="stylesheet" href="includes/css/style.php">
        
        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="includes/js/hoja2.js" ></script>
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
                &nbsp;&gt;&nbsp;<a href="#">Seleccion Mes</a>
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
            <div class="page"  align="center">
                <form id="fechaForm" name="fechaForm" method="post" action="reporteHoja2Programado.php">
                    <br>
                    <h2>Turnos Programados</h2>
                    <div class="logo">
                        <span style="font-size: 5em;" class="icon icon-edit"></span><h2>Ingrese Fecha</h2>
                    </div>
                    <br>
                    <br>
                    <input type="text" name="fecha" id="fecha">
                    <br>
                    <br>
                    <input class="button-secondary" type="submit" name="enviar" id="enviar" value="Consultar">
                </form>
            </div>
        </div>
    </body>
</html>