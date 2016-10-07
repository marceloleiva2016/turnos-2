<?
$FUNCION='M';
$AREA_P='COEXP';

include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//header("Cache-Control: private");
//conexion con la base
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);

   
include_once '/home/web/namespacesAdress.php';
//require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
//include_once  'includes/auxFunctions/ArrayDeInternaciones.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcInternacion . 'internacionDatabaseLinker.class.php';

include_once nspcSsf . 'formulario.class.php';
include_once nspcSsf . 'formViewer.class.php';

include_once nspcPacientes . 'pacienteDataBaseLinker.class.php';

include_once nspcCommons . 'utils.php';
$hcaDBLinker = new HcaDatabaseLinker();
$gralesDBLinker = new GeneralesDataBaseLinker();

if(!isset($_POST['scrollX'])|| empty($_POST['scrollX']) || !isset($_POST['scrollY'])|| empty($_POST['scrollY']))
{
	$scrollX = 0;
	$scrollY = 0;
}
else 
{
	$scrollX = Utils::postIntToPHP($_POST['scrollX']);
	$scrollY = Utils::postIntToPHP($_POST['scrollY']);
}

//$firePhp = FirePHP::getInstance(true);
//$firePhp->setEnabled(true);

//$firePhp->log($_POST, "POST");
$hcaId = $_POST['id'];
//$hcaId = 2;

$hca = new Hca($hcaId);
if ($hca->tipoHCA != TipoHCA::CAP) {
	throw new Exception('El ID de HCA no corresponde a una CAP', 20121113164200);
}

$hca->cargarTodoHCA(); 
$paciente = $hca->internacion->paciente;
$hayEgreso = $hca->hayEgresoHS();

//TODO
$idInternacion = $hcaDBLinker->getIdInternacion($hcaId);
$intDb = new InternacionDatabaseLinker();

session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario DAP</title>

	<link type="text/css" rel="Stylesheet" href="/tools/jquery/css2/jquery-ui-1.8.16.custom.css" /> 
	<link type="text/css" rel="Stylesheet" href="/tools/jquery/validity/css/jquery.validity.css" />
    
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/tablesorter/css/style.css" />
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/jqGrid/css/ui.jqgrid.css" />
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/contextMenu/jquery.contextMenu.css" />
    
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/jqtip/jquery.qtip.css" />
    <link type="text/css" rel="Stylesheet" href="includes/css/formularioHCA.css" />
    <link type="text/css" rel="Stylesheet" href="includes/css/compartido.css" />
    <link type="text/css" rel="Stylesheet" href="includes/css/formularioHCAImprimir.css" media="print" />
    <link type="text/css" rel="Stylesheet" href="includes/css/menu.css" />
    
    <!--  <link type="text/css" rel="Stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />-->
    
	<style><!--TODO: TODOS ESTOS ESTILOS LOS TENGO QUE SACAR A OTRO ARCHIVO DE ESTILOS-->
	</style>
	
    <script src="/tools/jquery/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="/tools/jquery/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
	  
	<script src="/tools/jquery/jqGrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="/tools/jquery/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    
    <script src="/tools/jquery/flot/js/jquery.flot.min.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/tools/jquery/validity/js/jquery.validity.js"></script>
    <script src="/tools/jquery/jqtip/jquery.qtip.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
    
	<script src="/tools/jquery/contextMenu/jquery.contextMenu.js" type="text/javascript"></script>
	<script src="includes/js/formularioHCA.js" type="text/javascript"></script>
	
	
	<script>
		frmOk = true;
	</script>
</head>

<body bgcolor = "#FFFFFF" onload="javascript:ResetScrollPosition(<?php echo "$scrollX";?>,<?php echo "$scrollY";?>)">

<script>
	var id = <?php echo $hcaId;?>;
	<?php if( $hayEgreso) {?>
		var hayEgreso = true;
	<?php } else  {?>
		var hayEgreso = false;
	<?php } ?>
</script>

<!-- Esta es la barra de direccion superior si la idea es imprimir la pagina 
entonces no deberia estar visible en ese momento -->
<div id="barra" >
	<div id = "barraImage" >
	</div>
	<div style="float: left;">
		<p>&nbsp;<a href="/sistemas.php3">Sistema Salud</a>&nbsp;&gt;
		<a href="../busqpacint.php3">Menu Internaciones</a>&nbsp;&gt;
		<a>Formulario DAP</a></p>
	</div>
	<div style="float: right; text-align: right; width: 200px; margin-top:-10px; margin-right:10px;">
	    <p>Usuario: <b><?php print $user_id;?></b><br />
	       <a href="#" id="btnCambiarUsuario">Cambiar Usuario</a>
		</p>
	</div>
	
</div>

<div id="contenedorGral">

<form action="formularioDAP.php">	

	<div id="contenedorMenuOpciones">
		
		<div id="menuOpciones">
			<ul>
				<li><a href="#" id="btnModificarLaboratorios">Modificar laboratorios</a></li>
				<li><a href="#" id="btnAgregarRayos">Rayos</a></li>
				<li><a href="#" id="btnAgregarAltaComplejidad">Alta Complejidad</a></li>
				<li><a href="#" id="btnAgregarObservaciones">Agregar Observacion </a></li>
				<li><a href="#" id="btnAgregarInterconsultas">Agregar Interconsulta </a></li>
				<li><a href="#" id="btnAgregarPendientes">Agregar Pendientes </a></li>
				<li><a href="#" id="btnModificarDiagnosticoIngreso">Modificar Diagn&oacute;stico de Ingreso</a></li>
				<li><a href="#" id="btnSalirDeDAP">Salida DAP</a></li>
				<li><a href="#" id="btnImprimir">Imprimir </a></li>
				<li><a href="#" id="btnCmbPacienteDAP">Cambiar Paciente</a></li>
			</ul>
		</div>
		
	</div>
	
	<div id="contenedor">
		<div id="tituloImprimir" class="cnt">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td align="left" width="25">
			    	<img src="../includes/images/malvinasargentinas.icon.png" alt="LogoMalvinas" border="0" width="18" height="20"/>
			    </td>
			    <td align="left">
			    	Secretar&iacute;a de Salud<br />
			    	Municipalidad de Malvinas Argentinas
			    	<?php 
			    		if($hca->intervPolicial)
			    		{
			    			echo "<b> IP</b>";
			    		}
			    	?>
			    </td>
			    <td align="right" width="50"><b>D.A.P.</b></td>
			  </tr>
			  <tr>
			    <td colspan="3" align="center">Historia Cl&iacute;nica Abreviada</td>
			  </tr>
			</table>
		</div>
		
		<div id="contenedorFicha" class="cnt" style="border: solid;border-width: 1pt;">
			<table id="tblFichaPaciente" class='hor-minimalist-b'>
				<tr>
					<td><b>Fecha: </b><?php print Utils::phpTimestampToHTMLDateTime($hca->getFechaIngreso()); ?></td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td><b>Nombre: </b><?php print $paciente->nombre; ?></td>
					<td><b>TipoDoc: </b><?php print $gralesDBLinker->tipoDocumento($paciente->tipoDoc); ?></td>
					<td><b>NroDoc: </b><?php print $paciente->nroDoc; ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Obra Social: </b>
					<?php echo Utils::phpStringToHTML($hca->obraSocail());?>
					</td>
					<td><b>Edad: </b><?php
						$edad = $paciente->getAnios($hca->getFechaIngreso());
						if ($edad > 0) {
							print Utils::phpStringToHTML($edad . " años");
						} 
						else
						{
							$edad = $paciente->getMeses($hca->getFechaIngreso());
							print Utils::phpStringToHTML($edad . " meses");
						}
					?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Domicilio: </b><?php print $paciente->direccion->direccion . ' - '. $paciente->direccion->localidad; ?></td>
					<td><b>Partido: </b><?php print $paciente->direccion->partido; ?></td>
				</tr>
				<tr>
					<td colspan="2"><b>Telefono: </b><?php print $paciente->telefono; ?></td>
					<td><b><span class="noImprimir">
					<?php 
						if($hca->intervPolicial)
						{
							echo "INTERVENCION POLICIAL";
						}
					?>
					</span>
					</b></td>
				</tr>
			</table>
			
		</div><!-- Fin div contenedorFicha -->
		
		
		<?php
			
			$nrodoc = $paciente->nroDoc;
			$tipodoc = $paciente->tipoDoc;
			$base = new PacienteDatabaseLinker();
			$prevalentes = $base->tieneAlertaPrevalentes($tipodoc, $nrodoc);  
			if($prevalentes)
			{
				
				$idFormulario = $base->idFormulariosPrevalente($tipodoc, $nrodoc);
				$form = new FormViewer($idFormulario);
		?>
		
		<div id="contenedorPrevalente" class="cnt" style="border: solid;border-width: 1pt;">
			<?php
			
				$elementosPorFila = 5;
				$elementosDibujados = 0;
				$i=0;
			?>
				
				<table cellspacing="8px" style="width: 100%">
				<thead>
				<tr>
					<th colspan="<?php echo $elementosPorFila; ?>" style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Enfermedades Prevalentes</th>
				</tr>
				</thead>
					<?php
						/* @var $pregunta Pregunta */
						foreach ($form->modelo->preguntas as $preguntas) {
							foreach ($preguntas as $pregunta) {
								if($i%$elementosPorFila==0)
								{
									echo "<tr>";
								}
								//escribo datos 
								
								echo '<td title="' . $pregunta->descripcion . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
									echo  $pregunta->toHtml();
									
								echo "</td>";
								
								
								if ($i%$elementosPorFila==$elementosPorFila-1)
								{
									echo "</tr>";	
								}
								$i++;
							}	
								
						}
						//Si tengo que cerrar la ultima fila
						if($i%$elementosPorFila!=0)
						{
							//echo '</tr>';
							if($i%$elementosPorFila>1)
							{
								echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila))).'"></td>';
								echo '</tr>';
							}
							else {
								echo '<td ></td>';
								echo '</tr>';
							}
						} 
					?>
				
				</table>

		</div>
		<?php
			}
			 
		?>
		
		<div id="contenedorDiagnosticos" style="clear: both; margin-left: 10px; padding: 0px; margin-top: 0px" >
			<table width="100%" id="tblDiagnosticos" >
				<tr>
					<td><b>Motivo de Consulta: </b>
						<?php 
							print Utils::phpStringToHTML($hca->motivoConsulta); 
						?>
					</td>
				</tr>
				<tr>
					<td><b>Diag. Ingreso: </b>
						<?php 
							print Utils::phpStringToHTML($hca->diagnosticoIngreso()); 
						?>
					</td>
				</tr>
			</table>
		</div><!-- Fin div contenedorDiagnosticos -->
		
		<div id="contenedorLaboratorios" class="cnt">
			<div id="divLaboratoriosSangre" style="clear: both;border: solid;border-width: 1pt;">
			<?php
				$tipo = TipoLaboratorio::SANGRE;
				$laboratorios = $hca->estudiosLaboratorioSangre; 
				
				$elementosPorFila = 8;
				$elementosDibujados = 0;
				$i=0;
			?>
				
				<table cellspacing="8px" style="width: 100%">
				<thead>
				<tr>
					<th colspan="<?php echo $elementosPorFila; ?>" style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Sangre</th>
				</tr>
				</thead>
					<?php
						/* @var $labo Laboratorio */
						foreach ($laboratorios as $labo) {	
							if($i%$elementosPorFila==0)
							{
								echo "<tr>";
							}
							//escribo datos 
							if($labo->esNumerico)
							{
								echo '<td title="' . $labo->descripcion . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
							}
							else 
							{
								echo '<td title="' . Utils::phpStringToHTML($labo->valor) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
							}
								echo "<b>". $labo->nombre . "</b><br />";
								echo $labo->valor;
							echo "</td>";
							
							
							if ($i%$elementosPorFila==7)
							{
								echo "</tr>";	
							}
							$i++;
						}
						//Si tengo que cerrar la ultima fila
						if($i%$elementosPorFila!=0)
						{
							//echo '</tr>';
							echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'"></td>';
							echo '</tr>';
						} 
					?>
				
				</table>
			</div>
			
			<div id="divLaboratoriosOrina" style="clear: both;border: solid;border-width: 1pt; margin-top: 15px;">
				
				<?php
				$laboratorios = $hca->estudiosLaboratorioOrina; 
				
				$elementosPorFila = 7;
				$elementosDibujados = 0;
				$i=0;
				?>
				
				<table cellspacing="8px" style="width: 100%">
				<thead>
				<tr>
					<th colspan=" <?php echo $elementosPorFila; ?>" style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Orina</th>
				</tr>
				</thead>
					<?php
						/* @var $labo Laboratorio */
						foreach ($laboratorios as $labo) {	
							if($i%$elementosPorFila==0)
							{
								echo "<tr>";
							}
							//escribo datos 
							if($labo->esNumerico)
							{
								echo '<td title="' . $labo->descripcion . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
							}
							else 
							{
								echo '<td title="' . Utils::phpStringToHTML($labo->valor) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
							}
								echo "<b>". $labo->nombre . "</b><br />";
								echo $labo->valor;
							echo "</td>";
							
							
							if ($i%$elementosPorFila==7)
							{
								echo "</tr>";	
							}
							$i++;
						}
	
						//Si tengo que cerrar la ultima fila
						if($i%$elementosPorFila!=0)
						{
							//echo '</tr>';
							
							echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'">&nbsp;</td>';
							echo '</tr>';
							
						}
					?>
				</table>
			</div>
		</div>
		
		<div id="contenedorEstudios" class="cnt">
			<div id="divRayos" >
				<table cellspacing="8px" style="width: 100%">
				<thead>
				<tr>
					<th style="width: 120px; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Rx</th>
					<th style="width: 50px; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
					<th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Observaciones</th>
				</tr>
				</thead>
					<?php
						/* @var $estudio Rayo*/
						foreach ($hca->estudiosRx as $estudio) {
							echo '<tr>';
							echo '<td title="' . $estudio->descripcion . '" nowrap>';
								echo "<b>". $estudio->nombre . "</b>";
							echo "</td>";
							echo '<td title="' . $estudio->descripcion . '">';
								echo ($estudio->valor?"Si":"No");
							echo "</td>";
							echo '<td title="' . $estudio->descripcion . '">';
								echo Utils::phpStringToHTML($estudio->observacion);
							echo "</td>";
							echo "</tr>";	
						}
					?>
				
				</table>
			</div>
			
			<div id="divAltaComplejidad">
				<table cellspacing="8px" style="width: 100%">
				<thead>
				<tr>
					<th style="width: 85%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Alta Complejidad</th>
					<th style="width: 15%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
				</tr>
				</thead> 
					<?php
						/* @var $estudio AltaComplejidad*/
						foreach ($hca->estudiosAltaComplejidad as $estudio) {
							echo '<tr>';
							echo '<td title="' . $estudio->descripcion . '" nowrap>';
								echo "<b>". $estudio->nombre . "</b>";
							echo "</td>";
							echo '<td title="' . $estudio->descripcion . '">';
								echo ($estudio->valor?"Si":"No");
							echo "</td>";
							echo "</tr>";	
						}
					?>
				</table>
			</div>
		</div>
		
		<div id="contenedorObservaciones" class="cnt" >
			<b>Observaciones/Tratamientos:</b>
			<div id="divObservaciones" class="lstTextos">
				<?php
					/*@var $observacion Observacion*/
					echo '<ul>';
					foreach ($hca->observaciones as $observacion) {
						//se crea lo que se va a mostrar como tooltip en el elemento
						$title = "<b>Fecha:</b>" .Utils::phpTimestampToHTMLDateTime($observacion->fecha) ."<br/>";
						$title .= "<b>Usuario:</b>" .Utils::phpStringToHTML($observacion->usuario) ."<br/>";
						
						echo "<span class=\" context-menu-one box menu-1\" obsId=\"".$observacion->id."\" obsUsr=\"".$observacion->usuario."\">\n";
						echo '<li class="tool" title="'.$title.'">';
							print Utils::phpStringToHTML($observacion->descripcion);
						echo '</li>';   
						echo "</span>";
					} 
					echo '</ul>';
				?>
			</div>
		</div>
		
		<div id="contenedorInterconsultas" class="cnt" >
			<b>Interconsultas:</b>
			<div id="divInterconsultas" class="lstTextos">
				<?php
					echo '<ul>';
					/*@var $interconsulta Interconsulta*/
					foreach ($hca->interconsultas as $interconsulta) {
						//se crea lo que se va a mostrar como tooltip en el elemento
						$title = "<b>Fecha:</b>" .Utils::phpTimestampToHTMLDateTime($interconsulta->fecha) ."<br/>";
						$title .= "<b>Usuario:</b>" .Utils::phpStringToHTML($interconsulta->usuario) ."<br/>";
						echo "<span class=\" context-menu-one box menu-1\" obsId=\"".$interconsulta->id."\" obsUsr=\"".$interconsulta->usuario."\">\n";
						echo '<li class="tool" title="'.$title.'">';
							print Utils::phpStringToHTML($interconsulta->descripcion);
						echo '</li>';  
						echo "</span>"; 
					} 
					echo '</ul>';
				?>
			</div>
		</div>
		
		<div id="contenedorPendientes" class="cnt" >
			<b>Pendientes:</b>
			<div id="divPendientes" class="lstTextos">
				<?php
					
					echo '<ul>';
					/*@var $pendiente Pendiente*/
					foreach ($hca->pendientes as $pendiente) {
						//se crea lo que se va a mostrar como tooltip en el elemento
						$title = "<b>Fecha:</b>" .Utils::phpTimestampToHTMLDateTime($pendiente->fecha) ."<br/>";
						$title .= "<b>Usuario:</b>" .Utils::phpStringToHTML($pendiente->usuario) ."<br/>";
						echo "<span class=\" context-menu-one box menu-1\" obsId=\"".$pendiente->id."\" obsUsr=\"".$pendiente->usuario."\">\n";
						echo '<li class="tool" title="'.$title.'">';
							print Utils::phpStringToHTML($pendiente->descripcion);
						echo '</li>';  
						echo "</span>"; 
					} 
					echo '</ul>';
				?>
			</div>
		</div>
		
		<div id="contenedorSalidaHS" class="cnt" >
			<table style="width: 100%">
			<thead>
			<tr>
				<th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Datos del Egreso (DAP)</th>
			</tr>
			</thead>
			<tbody>
			<?php 
				if ($hca->hayEgresoHS()) { 
			?>
					<table width="100%">
						<tr>
							<td><b>Fecha:</b>
								<?php
									print Utils::phpTimestampToHTMLDate($hca->salidaHS->fecha);
								?>
							</td>
							<td>&nbsp;</td>
							<td><b>Profesional:</b>
								<?php 
									print Utils::phpStringToHTML($hca->salidaHS->nombreProfesional());
								?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><b>Diag. Egreso:</b>
								<?php 
									echo "<span class=\" context-menu-one-egreso box menu-1\" diagId=\"".$hca->salidaHS->id."\" diagUsr=\"".$hca->salidaHS->usr."\">\n";
									print Utils::phpStringToHTML($hca->salidaHS->diagnosticoToString());
									echo "</span>\n";
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2"><b>Destino:</b>
								<?php 
									print Utils::phpStringToHTML($hca->salidaHS->destinoToString());
								?>
							</td>
							<td>
								<?php 
									if ($hca->salidaHS->destino == TipoDestinoHS::DERIVACIONint) {
										$centro = $gralesDBLinker->nombreCentro($hca->salidaHS->centro);
										print "<b>Centro:</b> " . Utils::phpStringToHTML($centro);
									} elseif ($hca->salidaHS->destino == TipoDestinoHS::INTERNACION) {
										$nombre = $gralesDBLinker->nombreEspecialidad($hca->salidaHS->servicio);
										print "<b>Servicio:</b> " . Utils::phpStringToHTML($nombre);
									} else {
										print "&nbsp;";
									}
								?>
							</td>
						</tr>
					</table>
			<?php
				} else {
			?>
					<table width="100%"> 
						<tr>
							<td><b>Fecha:</b></td>
							<td>&nbsp;</td>
							<td><b>Profesional:</b></td>
						</tr>
						<tr>
							<td colspan="3"><b>Diag. Egreso:</b></td>
						</tr>
						<tr>
							<td colspan="3"><b>Destino:</b></td>
						</tr>
					</table>
			<?php 	
				}
			?>
			</tbody>
			</table>
		</div>
		
		<!-- SOLO sale si hubo salida UDP y a HS -->
		<div id="contenedorFirmaSalidaHS" class="cnt">
			<table width="100%">
				<tr>
					<td align="center">...................................</td>
					<td align="center">...................................</td>
				</tr>
				<tr>
					<td align="center">Firma Profesional</td>
					<td align="center">Aclaraci&oacute;n</td>
				</tr>
			</table>
		</div>
		
		<div id="contenedorAnterioresUDPS" class="cnt" >
			
			<div class="ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix">
				<a id="btnEstudiosAnteriores" class="ui-jqgrid-titlebar-close HeaderButton" href="javascript:void(0)" role="link" style="right: 0px;">
					<span id = "iconoEstudios" class="ui-icon ui-icon-circle-triangle-s" style="float: left;"></span>
				</a>
				<span class="ui-jqgrid-title" style="font-size: small;float: left;">Estudios anteriores</span>
			</div>
			
			<div id="divEstudiosAnteriores" style="border: solid;border-width: 1pt;">
				<div id="tabs">
				    <ul>
				        <li><a href="includes/ajaxFunctions/UdpsAnteriores.php?id=<?php echo $hcaId;?>">UDPs</a></li>
				        <li><a href="includes/ajaxFunctions/internaciones.php?tipoDoc=<?php echo $hca->internacion->paciente->tipoDoc;?>&nroDoc=<?php echo $hca->internacion->paciente->nroDoc;?>">Internaciones</a></li>
				        <li><a href="includes/ajaxFunctions/historiaClinica.php?tipoDoc=<?php echo $hca->internacion->paciente->tipoDoc;?>&nroDoc=<?php echo $hca->internacion->paciente->nroDoc;?>">Hist. Clin</a></li>
				    </ul>
				    
				</div>
			</div>
			
		</div>
		
	
	</div><!-- Cierre de div contenedor -->
	
	
</form> 

</div>

<div id="dialogForm" style="display: none;"></div>

<div id="toolbar-container" style="display:none;">
</div>

<form action="formularioDAP.php" method="post" id="formHCA" onsubmit="javascript:SaveScrollPosition()">
	<input type="hidden" name="id" value="<?php echo $hcaId; ?>">
	<input type="hidden" id="idScrollX" name="scrollX" value="">
	<input type="hidden" id="idScrollY" name="scrollY" value="">
</form>

</body>
</html>