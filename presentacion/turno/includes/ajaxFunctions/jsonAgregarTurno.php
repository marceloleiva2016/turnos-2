<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$array = $_REQUEST;

$DBturno = new TurnoDatabaseLinker();
$dbConsultorios = new ConsultorioDatabaseLinker();

$idConsultorio = $dbConsultorios->getIdConsultorioDemanda($array['subespecialidad']);

//tipo de atencion DEMANDA equivale al id=1
$ingreso = $DBturno->crearTurnoDemanda($array,$idConsultorio,1);

$std = new stdClass();

$std->ret = $ingreso;

echo json_encode($std);