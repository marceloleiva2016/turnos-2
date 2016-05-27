<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'especialidadDatabaseLinker.class.php';

$db = new EspecialidadDatabaseLinker();

$data = $_POST;

$ret = $db->modificarEspecialidad($data);
    
echo json_encode($ret);

?>