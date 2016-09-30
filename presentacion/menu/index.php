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
		<meta name="keywords" content="horizontal, slide out, menu, navigation, responsive, javascript, images, grid" />
		<meta name="author" content="Juan Ferreyra" />
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
	        	&nbsp;&nbsp;&nbsp;<a href="#">Sistema SITU</a>
	        </div>
	        <!-- /navegar-->
	        <!-- usuario -->
            <div id="usuario">
                <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a> |
                <a href="../index.php?logout=1"><span class="icon icon-exit"> </span>Terminar sesión</a>
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
									<?php
									if ($data->tienePermiso('PROGRAMADO_ASIGNAR')){
										echo "<li><a href='../turno/asignarTurnoProgramado.php'><span>Asignar Turno</span></a></li>";
									}
									if ($data->tienePermiso('PROGRAMADO_CONFIRMAR')){
										echo "<li><a href='../turno/confirmarTurnoProgramado.php'><span>Confirmar Turno</span></a></li>";
									}
									if ($data->tienePermiso('PROGRAMADO_ATENCION')){
										echo "<li><a href='../atencion/preTurnoProgramado.php'><span>Antencion Medica</span></a></li>";	
									}
									if ($data->tienePermiso('PROGRAMADO_ELIMINAR')){
										echo "<li><a href='../turno/eliminarTurnoProgramado.php'><span>Eliminar Turno</span></a></li>";
									}
									?>
									<li><a href='../atencion_diaria/indexProgramado.php'><span>Imprimir Turnos Confirmados</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Demanda</a>
								<ul class="cbp-hssubmenu">
									<?php
									if ($data->tienePermiso('DEMANDA_ASIGNAR')){
										echo "<li><a href='../turno/asignarTurnoDemanda.php'><span>Asignar Turno</span></a></li>"	;
									}
									if ($data->tienePermiso('DEMANDA_ATENCION')){
										echo "<li><a href='../atencion/preTurnoDemanda.php'><span>Antencion Medica</span></a></li>";
									}
									?>
									<li><a href='../atencion_diaria/indexDemanda.php'><span>Imprimir Turnos Confirmados</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Administracion</a>
								<ul class="cbp-hssubmenu cbp-hssub-rows">
									<?php
									if ($data->tienePermiso('ALTA_PROFESIONAL')){
										echo "<li><a href='../profesional/'><span>Profesional</span></a></li>";
									}
									if ($data->tienePermiso('ALTA_ESPECIALIDAD')){
										echo "<li><a href='../especialidad/'><span>Especialidad</span></a></li>";
									}
									if ($data->tienePermiso('ALTA_SUBESPECIALIDAD')){
										echo "<li><a href='../subespecialidad/'><span>Subespecialidad</span></a></li>";
									}
									if ($data->tienePermiso('ALTA_CONSULTORIO')){
										echo "<li><a href='../consultorio/'><span>Consultorios</span></a></li>";
									}
									if ($data->tienePermiso('ADMINISTRAR_FERIADO')){
										echo "<li><a href='../feriados/'><span>Feriados / Vacaciones</span></a></li>";	
									}
									if ($data->tienePermiso('VER_TURNEROS')){
										echo "<li><a href='../turnero/'><span>Pantalla Llamados</span></a></li>";	
									}
									?>
								</ul>
							</li>
							<li>
								<a href="#">Paciente</a>
								<ul class="cbp-hssubmenu cbp-hssub-rows">
									<?php
									if ($data->tienePermiso('PACIENTE_CREAR')) {
										echo "<li><a href='../paciente/new.php'><span>Nuevo Paciente</span></a></li>";
									}
									?>
									<li><a href="../paciente/"><span>Consulta de Paciente</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Estadisticas</a>
								<ul class="cbp-hssubmenu cbp-hssub-rows">
									<li><a href="../estadisticas/hoja2.0/indexDemanda.php"><span>Hoja 2 Demanda</span></a></li>
									<li><a href="../estadisticas/hoja2.0/indexProgramado.php"><span>Hoja 2 Programado</span></a></li>
									<li><a href="../estadisticas/hoja2.1/"><span>Hoja 2.1</span></a></li>
								</ul>
							</li>
							<li>
								<a href="#">Internacion</a>
								<ul class="cbp-hssubmenu cbp-hssub-rows">
									<?php
									if ($data->tienePermiso('ABM_CAMA')){
										echo "<li><a href='../cama/'><span>Camas</span></a></li>";
									}
									if ($data->tienePermiso('ABM_SECTOR')){
										echo "<li><a href='../sector/'><span>Sector Fisico</span></a></li>";
									}
									if ($data->tienePermiso('INTERNACION')){
										echo "<li><a href='../internacion/'><span>Internados</span></a></li>";
									}
									?>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</div>

			<div class="imagenLogo">

			</div>
		</div>
		<script src="js/cbpHorizontalSlideOutMenu.min.js"></script>
		<script>
			var menu = new cbpHorizontalSlideOutMenu( document.getElementById( 'cbp-hsmenu-wrapper' ) );
		</script>
	</body>
</html>
