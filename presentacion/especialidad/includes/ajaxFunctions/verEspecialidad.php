<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'especialidadDatabaseLinker.class.php';

$db = new EspecialidadDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getEspecialidadesJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;

?>