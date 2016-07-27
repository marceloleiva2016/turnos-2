<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$db = new SubespecialidadDatabaseLinker();

$data = $_POST;

if($data['oper'] == 'del')
{
    $ret = $db->eliminarSubespecialidad($data);
}
else
{
    $ret = $db->modificarSubespecialidad($data);    
}    
echo json_encode($ret);
?>