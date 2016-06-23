<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
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

if(isset($_REQUEST['especialidad'])) {
    $idespecialidad = $_REQUEST['especialidad'];
}

$dbcons = new ConsultorioDatabaseLinker();

$consultorios = $dbcons->getConsultorios($idespecialidad);

?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Listado</title>
        <meta name="description" content="formulario para atencion en demanda para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />

        
        <link media="screen" type='text/css' rel="stylesheet" href="../includes/css/demo.css" >
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
        <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/login.css">
        <link type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
        <link media="screen" type="text/css" rel="stylesheet" href="includes/css/consultorio.css">

        <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
        <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
        <script type="text/javascript" src="includes/js/list.js" ></script>
    </head>
    <body>
        <!-- barra -->
        <div id="barra" >
            <!-- navegar -->
            <div id="barraImage" >
                <span style="font-size: 2em;" class="icon icon-about"></span>
            </div>
            <div id="navegar">
                &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="index.php">Fintro Consultorios</a>&nbsp;&gt;&nbsp;<a href="#">Listado Consultorios</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="../../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->
        <div id="container">
            <div id="demo" class="listadoPacientes"  align="center">
                <br><br>
                <table align="center" border="1" id="listado">
                    <tr>
                        <th>Nro</th>
                        <th>Tipo Consultorio</th>
                        <th>Subespecialidad</th>
                        <th>Profesional</th>
                        <th>Accion</th>
                    </tr>
                    <?php
                        for ($i=0; $i < count($consultorios); $i++) { 
                            echo "<tr><td>".$consultorios[$i]['id']."</td>
                                  <td>".$consultorios[$i]['tipo_consultorio']."</td>
                                  <td>".$consultorios[$i]['subespecialidad']."</td>
                                  <td>".$consultorios[$i]['profesional']."</td>
                                  <td><input class='button-secondary' type='button' value='VER' onclick=javascript:mostrarFormulario('".$consultorios[$i]['id']."');></td></tr>";
                        }
                    ?>
                </table>
                <input class="button-secondary"  type="button" onclick=" location.href='new.php' " value="CREAR" name="crear" />
            </div>
        </div>
        <form method="post" id="formConsultorio" target="_blank" >
            <input type="hidden" name="id" value="" id="id" />
        </form>

    </body>
</html>