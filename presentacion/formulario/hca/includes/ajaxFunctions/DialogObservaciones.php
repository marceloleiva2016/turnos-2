<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
//include_once  'includes/auxFunctions/ArrayDeInternaciones.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcInternacion . 'internacionDatabaseLinker.class.php';

$hcaId = $_POST['id'];
$base = new HcaDatabaseLinker();
$observaciones = $base->observaciones($hcaId);
?>

<div id="contenedorObservaciones" class="cnt" >
	<b>Observaciones/Tratamientos:</b>
	<div id="divObservaciones" class="lstTextos">
		<?php
			/*@var $observacion Observacion*/
			echo '<ul>';
			foreach ($observaciones as $observacion) {
				//se crea lo que se va a mostrar como tooltip en el elemento
				$title = "<b>Fecha:</b>" .Utils::phpTimestampToHTMLDateTime($observacion->fecha) ."<br/>";
				$title .= "<b>Usuario:</b>" .Utils::phpStringToHTML($observacion->usuario) ."<br/>";
				echo '<li class="tool" title="'.$title.'">';
					print Utils::phpStringToHTML($observacion->descripcion);
				echo '</li>';   
			} 
			echo '</ul>';
		?>
	</div>
</div>