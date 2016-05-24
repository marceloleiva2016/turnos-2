<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$db = new SubespecialidadDatabaseLinker();

$data = $_POST;

$respuesta = $db->crearSubespecialidad($data);

echo json_encode($respuesta);

?>