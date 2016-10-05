<?php

include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once negocio.'epicrisis.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
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

/*
$tipodoc = $_REQUEST['tipodoc'];
$nrodoc = $_REQUEST['nrodoc'];
$fechaIngreso = $_REQUEST['fecha_ingreso'];

$epicrisis = new Epicrisis($tipodoc, $nrodoc, $fechaIngreso);
$dbGenerales = new GeneralesDataBaseLinker();

$paciente = $epicrisis->internacion->paciente;
$antecedentes = $epicrisis->getAntecedentes();
$cronicaIntervenciones = $epicrisis->getCronicaIntervenciones();
$examenesComplementarios = $epicrisis->getExamenesComplementarios();
$intervencionesMenores = $epicrisis->getIntervencionesMenores();
$observaciones = $epicrisis->getObservaciones();
$medicacionHabitual = $epicrisis->getMedicacionHabitual();
$obraSocial = $dbGenerales->obraSocialPorCodigo($epicrisis->getObraSocial());
$nombreDiagIngreso = $dbGenerales->diagnosticoFromDiaglocal($epicrisis->getCodDiagnoIngreso());
$nombreDiagEgreso = $dbGenerales->diagnosticoFromDiaglocalXId($epicrisis->getDiagnosticoEgreso());
$fechaEgreso = $epicrisis->getFechaEgreso();
*/
?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title>Formulario Internacion</title>
		<meta name="description" content="Morphing Buttons Concept: Inspiration for revealing content by morphing the action element" />
		<meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
		<meta name="author" content="Juan Ferreyra" />

		<link rel="shortcut icon" href="/includes/img/malvinasargentinas.icon.png">
		<link type="text/css" rel="stylesheet" href="../includes/css/normalize.css" />
		<link type="text/css" rel="stylesheet" href="../includes/css/demo.css" />
		<link type="text/css" rel="stylesheet" href="../includes/css/component.css" />
		<link type="text/css" rel="stylesheet" href="../includes/css/content.css" />

		<link type="text/css" rel="stylesheet" href="../../includes/css/barra.css" />
		<link type="text/css" rel="stylesheet" href="../../includes/css/iconos.css" />
		
		<link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
		<link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />

		<link type="text/css" rel="stylesheet" href="includes/css/formularioEpicrisis.css" />
		<link type="text/css" rel="stylesheet" href="includes/css/formularioEpicrisisImprimir.css" media="print" />

<!--Scripts-->

		<script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
		<script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>

		<script type="text/javascript" src="../includes/js/modernizr.custom.js"></script>
		
		<script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>

		<script type="text/javascript" src="includes/js/formularioEpicrisis.js"></script>

		<script>
			var id = false;
			var tieneEgreso =false;
			<?php
			/*if($epicrisis->destino!=null)
			{
				echo "true";
			}
			else
			{
				echo "false";
			};*/
			?>;
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
				&nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Formulario Epicrisis</a>
			</div>
			<!-- /navegar-->
			<!-- usuario -->
			<div id="usuario">
				<a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
			</div>
			<!-- /usuario-->
		</div>
		<!-- /barra -->
		<div id="container" class="container">
			<section>
				<form>
					<div class="mockup-content">
						<div class="hoja">
							<div id="datosPaciente">
								<table width="100%">
									<tr>
										<td>
											<div align="center">
												Epicrisis
											<div>
										</td>
									</tr>
									<tr>
										<td>
											<table border="1" width="100%">
												<tr>
													<td>
														Nombre y Apellido<br />
														<?php/*Utils::phpStringToHTML($paciente->nombre);*/?>
													</td>
													<td>
														Sexo<br />
														<?php/*$paciente->sexo;*/?>
													</td>
													<td>
														Edad<br />
														<?php/*$paciente->edadToString(Utils::sqlDateTimeToPHPTimestamp($fechaIngreso));*/?>
													</td>
													<td>
														NHC<br />
														<?php/*Utils::phpStringToHTML($paciente->nroDoc);*/?>
													</td>
												</tr>
												<tr>
													<td>
														Fecha Ingreso<br />
														<?php/*Utils::phpTimestampToHTMLDateTime(Utils::sqlDateTimeToPHPTimestamp($fechaIngreso));*/?>
													</td>
													<td>
														O.S <br />
														<?php/*Utils::phpStringToHTML($obraSocial);*/?>
													</td>
													<td colspan="2">
														Fecha Egreso<br />
														<?php/*Utils::phpTimestampToHTMLDateTime(Utils::sqlDateTimeToPHPTimestamp($fechaEgreso));*/?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
							<div class="apartado">
								DIAGNOSTICO DE INGRESO:
								<?php/*Utils::phpStringToHTML($nombreDiagIngreso); */?>
							</div>
							<div class="apartado">
								DIAGNOSTICO DE EGRESO:
								<?php/*Utils::phpStringToHTML($nombreDiagEgreso); */?>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>ESTADO AL INGRESO</legend>
									<div class="cargado">
										<?php 
										/*echo $epicrisis->ingresoToHTML();*/
										?>
									</div>
								</fieldset>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>MEDICACION HABITUAL</legend>
									<div class="lstTextos">
										<?php
										/*if(count($medicacionHabitual)>0)
										{
											for ($i=0; $i < count($medicacionHabitual); $i++)
											{
												echo   "<li class='tool'>".$medicacionHabitual[$i]->toString()."</li>";
											}
										}
										else
										{
											echo "SIN INGRESAR";
										}
										*/?>
									</div>
								</fieldset>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>ANTECEDENTES</legend>
									<div class="cargado">
										<?php
										/*if(count($antecedentes)>0)
										{
											for ($i=0; $i < count($antecedentes); $i++)
											{
												echo   "<div class='antecedente'>".$antecedentes[$i]->toStringHTML()."</div>";
											}
										}
										else
										{
											echo "SIN INGRESAR";
										}
										*/?>
									</div>
								</fieldset>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>INTERVENCIONES</legend>
									<div class="cargado">
										<?php
										/*for ($i=0; $i < count($cronicaIntervenciones); $i++)
										{
											echo "<div class='cronicaIntervenciones'>".$cronicaIntervenciones[$i]->toStringHTMLEpicrisis()."</div>";
										}*/
										?>
									</div>
								</fieldset>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>EXAMENES COMPLEMENTARIOS</legend>
									<div class="cargado">
										<?php
										/*if(count($examenesComplementarios)>0)
										{
											for ($i=0; $i < count($examenesComplementarios); $i++)
											{
												echo   "<div class='examenCompl'>".$examenesComplementarios[$i]->toStringHTML()."</div>";
											}
										}
										else
										{
											echo "SIN INGRESAR";
										}*/
										?>
								</div>
								</fieldset>
							</div>
							<div class="apartado">
								<fieldset>
								<legend>INTERVENCIONES MENORES</legend>
									<div class="cargado">
										<?php
										/*for ($i=0; $i < count($intervencionesMenores); $i++)
										{
											echo "<div class='intervenciones'>".$intervencionesMenores[$i]->toStringHTMLEpicrisis()."</div>";
										}*/
										?>
									</div>
								</fieldset>
							</div>
							<?php
							for ($q=0; $q < count($observaciones); $q++)
                            {
                            ?>
                            <div class="apartado">
                            	<fieldset>
									<legend><?=$observaciones[$q]->getDetalle();?></legend>
	                                <div class="lstTextos">
	                                    <?php
	                                    /*$items = $observaciones[$q]->getitemsObservacion();
	                                    for ($y=0; $y < count($items); $y++)
	                                    {
	                                    	$title = "<b>Fecha:</b>" .$items[$y]->getFecha()."<br/>";
											$title .= "<b>Usuario:</b>" .$items[$y]->getUsuario()."<br/>";
											echo "<li class='tool' title='".$title."'>";
											echo "<span class=\" context-menu-one box menu-1\" obsId=\"".$items[$y]->getId()."\" obsUsr=\"".$items[$y]->getUsuario()."\">\n";
		                            	       	
		                                    print $items[$y]->getDetalle();
		                                        
	                                        echo "</span>";
	                                        if($epicrisis->destino==null)
											{
	                                        	echo "<input class='btnEditar' type='button' onclick='javascripst:editarObservacion(".$items[$y]->getId().");' value='editar' style='height:22px;width:49px;'>";
	                                        }
	                                        echo "</li>";
	                                    }*/
	                                    ?>
	                            	</div>
	                            </fieldset>
	                        </div>
	                       	<?php
	                   		}
	                   		?>	
							<div class="apartado">
								<fieldset>
								<legend>DESTINO</legend>
									<div class="cargado">
										<?php 
										/*echo $epicrisis->destinoToHTML();*/
										?>
									</div>
								</fieldset>
							</div>
							<br />
							<br />
							<div class="apartado">
								<table width="100%">
									<tr>
										<td align="left">
											FIRMA DEL MEDICO:<br />
											ACLARACION:<br />
											MATRICULA:
										</td>
										<td align="left">
											FAMILIAR<br />
											RESPONSABLE:<br />
											TELEFONO CONTACTO:
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</form>
			</section>
		</div><!-- /container -->
		<div id="menuMovible" class="morph-button morph-button-sidebar morph-button-fixed active open scroll">
			<button type="button"><span class="icon icon-menu"></span></button>
			<div class="morph-content">
				<div>
					<div class="content-style-sidebar">
						<span class="icon icon-delete"></span>
						<h2>Menú ingreso</h2>
						<ul>
							<li><a class="icon icon-playlist" href="#" id="agrEstIngreso">Estado al Ingreso</a></li>
							<li><a class="icon icon-edit" href="#" id="agrAntecedente">Antecedente</a></li>
							<li><a class="icon icon-design" href="#" id="agrMedHab">Medicacion Habitual</a></li>
							<li><a class="icon icon-calendar" href="#" id="agrIntervencion">Intervencion</a></li>
							<li><a class="icon icon-table" href="#" id="agrExamenCompl">Examen Complementario</a></li>
							<li><a class="icon icon-presentation" href="#" id="agrIntervMenor">Intervencion Menor</a></li>
							<li><a class="icon icon-design" href="#" id="agrProcQuirurgico">Procedimiento Quirurgico</a></li>
							<li><a class="icon icon-pentool" href="#" id="agrEvolucionClinica">Evolucion Clinica</a></li>
							<li><a class="icon icon-list" href="#" id="agrTratamientoMedico">Tratamiento Medico</a></li>
							<li><a class="icon icon-coding" href="#" id="agrInterrecurrencia">Interrecurrencia</a></li>
							<li><a class="icon icon-crop" href="#" id="agrRescateBacteriologico">Rescate Bacteriologico</a></li>
							<li><a class="icon icon-quote" href="#" id="agrTratamientoAlta">Tratamiento al Alta</a></li>
							<li><a class="icon icon-ruller" href="#" id="agrPendientes">Pendientes y Seguimiento</a></li>
							<li><a class="icon icon-exit" href="#" id="agrDiagEgr">Egreso</a></li>
							<li><a class="icon icon-printer" href="#" id="imprimir">Imprimir</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div><!-- morph-button -->
		<div id="dialogForm" style="display: none;"></div>
		<script src="../includes/js/classie.js"></script>
		<script src="../includes/js/uiMorphingButton_fixed.js"></script>
		<script>
			(function() {
				var docElem = window.document.documentElement, didScroll, scrollPosition,
					container = document.getElementById( 'container' );

				// trick to prevent scrolling when opening/closing button
				function noScrollFn() {
					window.scrollTo( scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0 );
				}

				function noScroll() {
					window.removeEventListener( 'scroll', scrollHandler );
					window.addEventListener( 'scroll', noScrollFn );
				}

				function scrollFn() {
					window.addEventListener( 'scroll', scrollHandler );
				}

				function canScroll() {
					window.removeEventListener( 'scroll', noScrollFn );
					scrollFn();
				}

				function scrollHandler() {
					if( !didScroll ) {
						didScroll = true;
						setTimeout( function() { scrollPage(); }, 60 );
					}
				};

				function scrollPage() {
					scrollPosition = { x : window.pageXOffset || docElem.scrollLeft, y : window.pageYOffset || docElem.scrollTop };
					didScroll = false;
				};

				scrollFn();
				
				var el = document.querySelector( '.morph-button' );
				
				new UIMorphingButton( el, {
					closeEl : '.icon-delete',
					onBeforeOpen : function() {
						// don't allow to scroll
						noScroll();
						// push main container
						classie.addClass( container, 'pushed' );
					},
					onAfterOpen : function() {
						// can scroll again
						canScroll();
						// add scroll class to main el
						classie.addClass( el, 'scroll' );
					},
					onBeforeClose : function() {
						// remove scroll class from main el
						classie.removeClass( el, 'scroll' );
						// don't allow to scroll
						noScroll();
						// push back main container
						classie.removeClass( container, 'pushed' );
					},
					onAfterClose : function() {
						// can scroll again
						canScroll();
					}
				} );
			})();
		</script>

		<form action="formularioEpicrisis.php" method="post" id="formEpicrisis">
			<input type="hidden" name="tipodoc" value='<?=$tipodoc; ?>'>
			<input type="hidden" name="nrodoc" value='<?=$nrodoc; ?>'>
			<input type="hidden" name="fecha_ingreso" value="<?php echo $fechaIngreso; ?>">
		</form>
	</body>
</html>