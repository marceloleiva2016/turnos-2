<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcPacientes . 'pacienteDataBaseLinker.class.php';
include_once nspcInternacion . 'internacionesManager.class.php';
include_once nspcInternacion . 'internacion.class.php';
include_once nspcInternacion . 'evento.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';

$tipoDoc = Utils::postIntToPHP($_POST['tipoDoc']);
$nroDoc = Utils::postIntToPHP($_POST['nroDoc']);

$int = new InternacionesManager();
$int->cargarInternacionesPaciente($tipoDoc, $nroDoc);

$internaciones = array();


$generalesDB = new GeneralesDataBaseLinker();
//$diagnosticos = $general->diagnosticosFromDiaglocal();
//$especialidades = $generalesDB->especialidades();
$centros = $generalesDB->centrosConInternaciones();


$response->page = 1;
$response->total = 1;
$response->records=count($int->internaciones);




$i=0;
/* @var $internacionId IdInternacion */
foreach ($int->internaciones as $internacionId)
{
	
	$internacionActual =new Internacion(); 
	$internacionActual->setId($internacionId);
	//TODO ver si se puede cambiar por cargar Ingreso y Egreso
	//$internacionActual->cargarEventos();
	$internacionActual->cargarIngresoYEgreso();
	
	$response->rows[$i]['id'] = $i+1;
			
	$row = array();
	//$row[] = '<a href="#" onclick="javascript:irAAnio('.$result["anio"].');">' . htmlentities($result["anio"]) . '</a>';

	/* @var $ingreso Ingreso */
	$ingreso = $internacionActual->dameIngreso();
	
	$row[] = Utils::phpTimestampToHTMLDate($ingreso->fecha);
	$row[] = htmlentities($centros[$ingreso->centro]);
	$row[] = htmlentities($generalesDB->especialidadPorCodigo($ingreso->servicio));
	$row[] = htmlentities($generalesDB->diagnosticoFromDiaglocal($ingreso->diagnostico));
	
	/* @var $ingreso Egreso */
	$egreso = $internacionActual->dameEgreso();
	$row[] = Utils::phpTimestampToHTMLDate($egreso->fecha);
	$row[] = htmlentities($generalesDB->diagnosticoFromDiaglocal($egreso->diagnostico));
	$row[] = "<a target='_blank' href='/produ/informatica/detalleDeInternacion.php?tipoDoc=". $internacionId->tipoDoc ."&nroDoc=".$internacionId->nroDoc ."&fechaIngreso=".$internacionId->fechaIngreso."'>". "Detalle"."</a>";
			
//			$row[] = '';  //Para el boton de detalle
	$response->rows[$i]['cell'] = $row; 
	$i++;
}

echo json_encode($response);

?>