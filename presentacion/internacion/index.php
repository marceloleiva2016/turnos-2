<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'sectorDatabaseLinker.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);

$dbSector = new SectorDatabaseLinker();

$sectores = $dbSector->getSectores();
?>
<!DOCTYPE>
<htmls>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Internacion</title>
		<link rel="shortcut icon" href="../favicon.ico">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.php">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
		<link rel="stylesheet" type="text/css" href="includes/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="includes/fonts/font-awesome-4.3.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="includes/css/style1.php" />
		
		<script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
		<script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
		<script src="includes/js/modernizr.custom.js"></script>
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
	        	&nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Internacion</a>
	        </div>
	        <!-- /navegar-->
	        <!-- usuario -->
            <div id="usuario">
                <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
		</div>
		<!-- /barra -->
		<div class="container">
			<button id="menu-toggle" class="menu-toggle"><span>Menu</span></button>
			<div id="theSidebar" class="sidebar">
				<button class="close-button fa fa-fw fa-close"></button>
				<h1>
					<span>Listado<span>
					Internacion
				</h1>
				<div class="related">
					<h3>Salas</h3>
					<?php
					for ($z=0; $z < count($sectores); $z++) {
						echo "<a onclick=javascript:cargarSector('".$sectores[$z]->getId()."');>".$sectores[$z]->getDetalle()."</a>";
					}
					?>
				</div>
				<div  class="related" >
					<h3>Acciones</h3>
					<?php
					if ($data->tienePermiso('INTERNACION_ASIGNAR')){
						echo "<a href='../internacion/new.php'><span>Internar Paciente</span></a>";
					}
					?>
				</div>
			</div>
			<div id="theGrid" class="main">
				
			</div>
		</div><!-- /container -->
		<form method="post" id="formSeleccionarInternacion" target="_blank" >
		    <input type="hidden" name="id" value="" id="id" />
		</form>
	</body>
</html>
