<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';
include_once datos.'internacionDatabaseLinker.class.php';

session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$anio = $_REQUEST['anio'];

$mes = $_REQUEST['mes'];

?>
<!DOCTYPE html>
<html lang="es" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Turnos Atendidos</title>
        <meta name="description" content="formulario para atencion en demanda para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/demo.php">
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/barra.php">
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/css/iconos.css">
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link media="screen" type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />
        <!--NOTIFICACION -->
        <link rel="stylesheet" type="text/css" href="../../includes/plug-in/notificacion/css/ns-default.css" />
        <link rel="stylesheet" type="text/css" href="../../includes/plug-in/notificacion/css/ns-style-attached.css" />
        <script src="../../includes/plug-in/notificacion/js/modernizr.custom.js"></script>
        <!--/NOTIFICACION -->
        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>
        <script type="text/javascript" src="includes/js/list.js" ></script>
        <script type="text/javascript">
            var anio = <?php echo $anio; ?>;
            var mes = <?php echo $mes; ?>;
        </script>
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
                &nbsp;&gt;&nbsp;<a href="preList.php">Turnos Pacientes Prefiltro</a>
                &nbsp;&gt;&nbsp;<a href="#">Lista</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->

        <div id="container" class="container">
            <div align="center">
                <h1>Listado Turnos Atendidos  <?php echo $mes."/".$anio;?></h1>
                <table id="jgVerTurnosAtendidos"></table>
                <div id="jgVerTurnosAtendidosFoot"></div>
            </div>
        </div>
        <form method="post" id="formSeleccionarInternacion" target="_blank" >
            <input type="hidden" name="id" value="" id="id" />
        </form>
    </body>
</html>