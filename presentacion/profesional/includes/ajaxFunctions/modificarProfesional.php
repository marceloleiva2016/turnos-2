<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'profesionalDatabaseLinker.class.php';

$db = new ProfesionalDatabaseLinker();

$data = $_POST;

$ret = $db->modificarProfesional($data);
    
echo json_encode($ret);

?>