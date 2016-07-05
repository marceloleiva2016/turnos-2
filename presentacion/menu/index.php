<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);

/*fin de agregado usuario*/
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Menu</title>
		<meta name="description" content="Blueprint: Horizontal Slide Out Menu" />
		<meta name="keywords" content="horizontal, slide out, menu, navigation, responsive, javascript, images, grid" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body>
		<!-- barra -->
		<div id="barra" >
			<!-- navegar -->
	 		<div id="barraImage" >
	 			<span style="font-size: 2em;" class="icon icon-about"></span>
	        </div>
	        <div id="navegar">
	        	&nbsp;&nbsp;&nbsp;<a href="#">Sistema SITU</a>&nbsp;&gt;
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
			<header class="clearfix">
			</header>	
			<div class="main">
				<nav class="cbp-hsmenu-wrapper" id="cbp-hsmenu-wrapper">
					<div class="cbp-hsinner">
						<ul class="cbp-hsmenu">
							<li>
								<a href="#">Turnos Programados</a>
								<ul class="cbp-hssubmenu">
									<li><a href="#"><span>Asignar Turno</span></a></li>
									<li><a href="#"><span>Confirmar Turno</span></a></li>
									<li><a href="#"><span>Listado de pacientes en espera</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Demanda</a>
								<ul class="cbp-hssubmenu">
									<li><a href="../turno/asignarTurnoDemanda.php"><span>Asignar Turno</span></a></li>
									<li><a href="../atencion/"><span>Antencion Medica</span></a></li>
									<li><a href="../estadisticas/hoja2.0/"><span>Hoja 2</span></a></li>
									<li><a href="../estadisticas/hoja2.1/"><span>Hoja 2.1</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Administracion</a>
								<ul class="cbp-hssubmenu cbp-hssub-rows">
									<li><a href="../profesional/"><span>Profesional</span></a></li>
									<li><a href="../paciente/paciente.php"><span>Paciente</span></a></li>
									<li><a href="../especialidad/"><span>Especialidad</span></a></li>
									<li><a href="../subespecialidad/"><span>Subespecialidad</span></a></li>
									<!--<li><a href="#"><span>Usuarios</span></a></li>-->
									<li><a href="../consultorio/"><span>Consultorios</span></a></li>
									<li><a href="../feriados/"><span>Feriados / Vacaciones</span></a></li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<script src="js/cbpHorizontalSlideOutMenu.min.js"></script>
		<script>
			var menu = new cbpHorizontalSlideOutMenu( document.getElementById( 'cbp-hsmenu-wrapper' ) );
		</script>
	</body>
</html>
