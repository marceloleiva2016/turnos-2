<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$idturno = $_REQUEST['idturno'];

$iduser =  $_REQUEST['idusuario'];

$DBturno = new TurnoDatabaseLinker();

$ingreso = $DBturno->insertarEnLog($idturno, 0, $iduser);

$std = new stdClass();

$std->ret = $ingreso;

echo json_encode($std);