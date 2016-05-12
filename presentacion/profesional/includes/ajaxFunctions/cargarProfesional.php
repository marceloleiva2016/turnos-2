<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'profesionalDatabaseLinker.class.php';

$db = new ProfesionalDatabaseLinker();

$data = $_POST;

$respuesta = $db->crearProfesional($data);

echo json_encode($respuesta);

?>