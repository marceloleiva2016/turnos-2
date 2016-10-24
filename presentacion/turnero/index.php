<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
    die();
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

?>
<!DOCTYPE html>
<html>
<head>
    <title>Turneros</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.php' >
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.php">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />
    <!--NOTIFICACION -->
    <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-default.css" />
    <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-style-attached.css" />
    <script src="../includes/plug-in/notificacion/js/modernizr.custom.js"></script>
    <!--/NOTIFICACION -->
    <link rel="stylesheet" type="text/css" href="../includes/plug-in/dialogo/dialog.css" />
    <script type="text/javascript" src="../includes/plug-in/dialogo/dialogModernizrCustom.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>
    <script type="text/javascript" src="includes/js/index.js"></script>
</head>
<body>
    <!-- barra -->
    <div id="barra" >
        <!-- navegar -->
        <div id="barraImage" >
            <span style="font-size: 2em;" class="icon icon-about"></span>
        </div>
        <div id="navegar">
            &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Turneros</a>
        </div>
        <!-- /navegar-->
        <!-- usuario -->
        <div id="usuario">
            <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
        </div>
        <!-- /usuario-->
    </div>
    <!-- /barra -->
    <div id="container" align="center">
        <br>
        <h2>Turneros Generados</h2>
        <br>
        <table id="jqTurneros"></table>
        <div id="jqTurnerosFoot"></div>
        <input class="button-secondary" type="submit" value="Nuevo" id="btnNuevoTurnero"  data-dialog="somedialog" <?php
        if (!$data->tienePermiso('NUEVO_TURNERO')){
            echo " style='display:none;' ";
        }
        ?> >
        <input type="submit"  data-dialogo="somedialog2" id="verTurnero" style='display:none;'>
    </div>
    <!--dialogo 1 -->
    <div id="somedialog" class="dialog">
      <div class="dialog__overlay">
      </div>
      <div class="dialog__content">
        <button id="somedialog-close" class="action" data-dialog-close>X</button>
        <div id="dialog_subcontent">

        </div>
      </div>
    </div>
    <!--dialogo 2 -->
    <div id="somedialog2" class="dialog">
        <div class="dialog__overlay">
        </div>
        <div class="dialog__content">
            <button id="somedialog2-close" class="action" data-dialog-close>X</button>
            <div id="dialog_subcontent2">

            </div>
        </div>
    </div>
    <!--DIALOGOS -->
    <script type="text/javascript" src="../includes/plug-in/dialogo/dialogFx.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/dialogo/dialogClassie.js" ></script>
    <!--/DIALOGOS -->
    <!--NOTIFICACION -->
    <script src="../includes/plug-in/notificacion/js/classie.js"></script>
    <script src="../includes/plug-in/notificacion/js/notificationFx.js"></script>
    <!--/NOTIFICACION -->
    <script>
        (function() {

            var dlgtrigger = document.querySelector( '[data-dialog]' ),

              somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog' ) ),

              dlg = new DialogFx( somedialog );

            dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );

            var dlgtrigger = document.querySelector( '[data-dialogo]' ),

                somedialog2 = document.getElementById( dlgtrigger.getAttribute( 'data-dialogo' ) ),

                dlg2 = new DialogFx( somedialog2 );

            dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg2) );

        })();
    </script>
</body>
</html>