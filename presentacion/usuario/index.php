<?
/*Agregado para que valide los usuarios*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
session_start();
$usuario = $_SESSION['usuario'];
$data = unserialize($usuario);
/*fin de agregado para que valide los usuarios*/
$entidad = $_SESSION['entidad'];

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Usuario</title>
        
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">

        <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />

        <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
        <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>
        <script type="text/javascript" src="includes/js/usuarios.js"></script>
    </head>
    <body style="background-image: url(includes/images/pattern.png);">
        <!-- barra -->
        <div id="barra" >
            <!-- navegar -->
            <div id="barraImage" >
                <span style="font-size: 2em;" class="icon icon-about"></span>
            </div>
            <div id="navegar">
                &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="">Usuarios</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="#"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->
        <div class="container">
            <div id="dialogAgregarUsuario" style="visibility:hidden;"> </div>
            <div id="dialogEliminarUsuario" style="visibility:hidden;"> </div>
            <div id="dialogForm" style="visibility:hidden;"> </div>

            <table align="center">
                <tr>
                    <td>
                        <div id="logo">
                            <h1>Usuarios</h1>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="content">
                            <ul class="bmenu">
                            <?php
                            if ($data->tienePermiso('CREAR_USUARIO')){ echo " <li><a id='btnAgregarUsuario' >Nuevo usuario</a></li>";}
                            if ($data->tienePermiso('ELIMINAR_USUARIO')){ echo "<li><a id='btnEliminarUsuario' >Baja de usuario</a></li>";}
                            if ($data->tienePermiso('VER_USUARIOS')){ echo "<li><a id='btnVerUsuarios' >Ver usuarios</a></li>";}
                            echo "<li><a id='btnVerMiUsuario' >Mi Usuario</a></li>";
                            ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
            <div id="page" >

            </div>
            <input type="hidden" id='entidad' value="<?php echo $entidad; ?>" >
    </body>
</html>
