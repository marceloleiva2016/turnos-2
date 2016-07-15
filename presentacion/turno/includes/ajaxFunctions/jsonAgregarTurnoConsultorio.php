<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$array = $_REQUEST;

$DBturno = new TurnoDatabaseLinker();
$dbConsultorios = new ConsultorioDatabaseLinker();

$consultorio = $dbConsultorios->getConsultorioPorDatos($array['subespecialidad'], $array['profesional']);

//tipo de atencion CONSULTORIO equivale al id=2

$ingreso = $DBturno->crearTurnoProgramado($array,$consultorio['id'] ,2);

$std = new stdClass();

$std->ret = $ingreso;

echo json_encode($std);