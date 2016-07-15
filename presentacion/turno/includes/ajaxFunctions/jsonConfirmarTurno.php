<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$idturno = $_REQUEST['idturno'];

$DBturno = new TurnoDatabaseLinker();

$ingreso = $DBturno->actualizarEstadoTurno($idturno, 2);

$std = new stdClass();

$std->ret = $ingreso;

echo json_encode($std);