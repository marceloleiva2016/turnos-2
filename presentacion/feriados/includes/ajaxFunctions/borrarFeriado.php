<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'feriadoDatabaseLinker.class.php';

$db = new FeriadoDatabaseLinker();

$data = $_POST;

if($data['oper'] == 'del')
{
    $ret = $db->eliminarFeriado($data['id']);
}
   
echo json_encode($ret);
?>