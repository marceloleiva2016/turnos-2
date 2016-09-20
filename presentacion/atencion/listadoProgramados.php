<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';
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

$idsubespecialidad = $_REQUEST['subespecialidad'];
$idprofesional = $_REQUEST['profesional'];

$dbProf = new ProfesionalDatabaseLinker();
$profesional = $dbProf->getProfesional($idprofesional);

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
  <script type="text/javascript" src="includes/js/listaAtencionProgramado.js" ></script>
  <!--progressButton -->
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/progressButton/css/btnProgressComponent.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/progressButton/css/btnProgressDemo.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/progressButton/css/btnProgressNormalize.css" />
  <script type="text/javascript" src="../includes/plug-in/progressButton/js/btnProgressModernizr.custom.js" ></script>
  <!--/progressButton -->
  <script type="text/javascript">
    var sub = <?php echo $idsubespecialidad; ?>;
    var prof = <?php echo $idprofesional; ?>;
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
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="preTurnoProgramado.php">Atencion Medica Programados</a>
        &nbsp;&gt;&nbsp;<a href="#">Listado Programados</a>
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
 		<label>Subespecialidad :</label><?php echo $subesp->getDetalle(); ?><br>
 		<label>Profesional :</label><?php echo $profesional->nombre." ".$profesional->apellido; ?>
  </div>
  	 
  
    <div id="demo" class="listadoPacientesProgramados"  align="center">
      
    </div>
  </div>

  <form method="post" id="frmSeleccionarPaciente" target="_blank" >
    <input type="hidden" name="id" value="" id="id" />
  </form>
  <script src="../includes/plug-in/progressButton/js/btnProgressClassie.js"></script>
  <script src="../includes/plug-in/progressButton/js/btnProgressProgressButton.js"></script>
</body>
</html>