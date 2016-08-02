<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once datos.'especialidadDatabaseLinker.class.php';
include_once datos.'utils.php';
/*DEMANDA*/

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);

if(!isset($_REQUEST['tipodoc']) or !isset($_REQUEST['nrodoc'])) {
  echo "<div align='center'>
          <h3>Datos de Paciente Inexistentes</h3>
        </div>";
  die();
} else {
  $tipodoc = $_REQUEST['tipodoc'];
  $nrodoc = $_REQUEST['nrodoc'];
}

$pacDB = new PacienteDatabaseLinker();
$paciente = $pacDB->getDatosPacientePorNumero($tipodoc, $nrodoc);
$nombre = Utils::phpStringToHTML($paciente->getNombre()." ".$paciente->getApellido());

$atencionDB = new AtencionDatabaseLinker();
$atenciones = $atencionDB->getAtencionesEnPaciente($tipodoc, $nrodoc, null);

//Especialidades
$espDb = new EspecialidadDatabaseLinker();
$ListaEspecialidades = $espDb->getEspecialidades();
$especialidadesRows = $ListaEspecialidades->data;

/*fin de agregado usuario*/
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>HC <?php echo $nombre; ?></title>
		<meta name="keywords" content="timeline, vertical, layout, style, component, web development, template, responsive" />
		<meta name="author" content="Juan Ferreyra" />
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
		<script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  		<script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
		<script type="text/javascript" src="js/index.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script type="text/javascript">
			
			var tipodoc = <?php echo $tipodoc; ?>;
			var nrodoc = <?php echo $nrodoc; ?>;

			function llenarFormularios(){
				<?php
				for ($i=0; $i < count($atenciones); $i++) {
					echo "$('#atencionNro".$atenciones[$i]['idatencion']."').load('../formulario".$atenciones[$i]['ubicacion']."formResumen.php?id=".$atenciones[$i]['idatencion']."');";
				}
				?>
			}

			$(document).ready(function(){
				llenarFormularios();
			});
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
	        	&nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Historia Clinica</a>
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
			<div align="center">
				<h2><?php echo $nombre; ?></h2>
				NHC : <?php echo $paciente->getNrodoc(); ?><br/>
                Sexo : <?php echo $paciente->getSexoLargo(); ?><br/>
                Edad : <?php echo $paciente->getEdadActual(); ?><br/>
			</div>
			<div class="filtroHC">
				<fieldset>
	        		<legend>Filtro</legend>
			        <select name="especialidad" id="especialidad" onchange="seleccionadoFiltro(this);" >
			          <option value="">Ninguno</option>
			          <?php
			            for ($i=0; $i < count($especialidadesRows); $i++) {
			              echo "<option value=".$especialidadesRows[$i]->id.">".$especialidadesRows[$i]->detalle."</option>";
			            }
			          ?>
			        </select>
		     	</fieldset>
	     	</div>
			<div class="main" id="listaDeAtenciones">
				<ul class="cbp_tmtimeline">
					<?php
					for ($i=0; $i < count($atenciones); $i++) {
					?>
					<li>
						<time class="cbp_tmtime" datetime='<?php echo $atenciones[$i]['fecha_creacion']; ?>'>
							<span><?php echo Utils::sqlDateToHtmlDate($atenciones[$i]['fecha']); ?></span>
							<span><?php echo $atenciones[$i]['hora']; ?></span>
						</time>
						<div class='<?php echo "icon ".$atenciones[$i]['icono']; ?>'>
						</div>
						<div class="cbp_tmlabel">
							<h1><?php echo $atenciones[$i]['tipo_atencion']; ?></h1>
							<h2><?php echo $atenciones[$i]['especialidad'].' | '.$atenciones[$i]['subespecialidad'].' | '.$atenciones[$i]['profesional']; ?></h2>
							<p id='<?php echo "atencionNro".$atenciones[$i]['idatencion']; ?>' ></p>
						</div>
					</li>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
	</body>
</html>
