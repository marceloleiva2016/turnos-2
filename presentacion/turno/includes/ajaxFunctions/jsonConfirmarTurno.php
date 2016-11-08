<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$idturno = $_REQUEST['idturno'];

$iduser =  $_REQUEST['idusuario'];

$DBturno = new TurnoDatabaseLinker();

$ingreso = $DBturno->insertarEnLog($idturno, 2, $iduser);

$std = new stdClass();

$std->idturno = $idturno;

$std->ret = $ingreso;

echo json_encode($std);