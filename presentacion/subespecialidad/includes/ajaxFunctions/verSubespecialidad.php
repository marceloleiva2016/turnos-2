<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$db = new SubespecialidadDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getSubespecialidadesJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;

?>