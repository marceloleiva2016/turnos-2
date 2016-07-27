<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'especialidadDatabaseLinker.class.php';
include_once datos.'generalesDatabaseLinker.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$gen = new GeneralesDatabaseLinker();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Buscar Paciente</title>
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
    <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    <script type="text/javascript" src="includes/js/index.js" ></script>
</head>
<body>
    <!-- barra -->
    <div id="barra" >
        <!-- navegar -->
        <div id="barraImage" >
            <span style="font-size: 2em;" class="icon icon-about"></span>
        </div>
        <div id="navegar">
            &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Busqueda de Paciente</a>
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
         <div style="display: inline-block;">
            <div style="word-break: break-all;">
                <h4>Buscar Paciente</h4><br><br>
                <div>
                    <select id="tipodoc" name="tipodoc" >
                      <?php
                      $td = $gen->getTiposDocumentos();
                      for ($i=0; $i < count($td); $i++) { 
                        echo "<option value=".$td[$i]['id'].">".$td[$i]['detalle_corto']."</option>";
                      }
                      ?>
                    </select>
                    <input type="text" name="nrodoc" id="nrodoc" placeholder="Nro Documento"/><br>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre"/><br>
                    <button id="buscar">Buscar</button>
                </div>
                <div id="miCargando" style="display:none;">
                    <p align="center">
                        <img id="loading" src="../includes/images/loader.gif" alt="Cargando..."/>
                        Espere mientras carga
                    </p>
                </div>
            </div>
            <div id="fichaPaciente" style="display:none;">

            </div>
        </div>
    </div>
    <input type="hidden" id="idusuario" name="idusuario" value=<?php echo $data->getId();?> >
</body>
</html>