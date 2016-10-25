<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'camaDatabaseLinker.class.php';

$db = new CamaDatabaseLinker();

$data = $_POST;

if($data['oper'] == 'del')
{
    $ret = $db->eliminarCama2($data);
}
else
{
    $ret = $db->modificarCama($data);
}    
echo json_encode($ret);
?>