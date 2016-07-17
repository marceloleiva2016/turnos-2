<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$idsubespecialidad = $_REQUEST['subespecialidades'];

$dbSubs = new SubespecialidadDatabaseLinker();
$subesp = $dbSubs->getSubespecialidad($idsubespecialidad);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Atencion</title>
  <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <link rel="stylesheet" type="text/css" media="screen" href="../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="includes/css/style.css">

  <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>
  <script type="text/javascript" src="includes/js/listaAtencionDemanda.js" ></script>

  <script type="text/javascript">
    var sub = <?php echo $idsubespecialidad; ?>;
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
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="index.php">Atencion Medica</a>&nbsp;&gt;&nbsp;<a href="#">Lista Demanda</a>
    </div>
    <!-- /navegar-->
    <!-- usuario -->
    <div id="usuario">
        <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
    </div>
    <!-- /usuario-->
  </div>
  <!-- /barra -->
  <div id="container">
  	<div  align="center">
 		<label>Subespecialidad :</label><?php echo $subesp->getDetalle(); ?>
  	</div>
    <div id="demo" class="listadoPacientes"  align="center">
      
    </div>
  </div>

  <form method="post" id="frmSeleccionarPaciente" target="_blank" >
    <input type="hidden" name="id" value="" id="id" />
  </form>
  
</body>
</html>