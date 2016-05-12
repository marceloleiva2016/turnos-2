<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';

$cp=$_REQUEST['cp'];

$gen = new GeneralesDatabaseLinker();

$citys = $gen->getCiudades_json($cp);

echo json_encode($citys);