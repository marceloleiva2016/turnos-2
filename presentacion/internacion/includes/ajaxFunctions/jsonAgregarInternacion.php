<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$req = $_REQUEST;

$dbInternacion = new InternacionDatabaseLinker();

$idingreso = $dbInternacion->crearInternacion($req['tipoDoc'], $req['nroDoc'], 0, $req['motivo_ingreso'], $req['diagnostico_ingreso'], $req['cama'], $req['idusuario']);

echo json_encode($idingreso);