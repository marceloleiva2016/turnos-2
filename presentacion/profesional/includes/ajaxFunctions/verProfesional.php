<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'profesionalDatabaseLinker.class.php';

$db = new ProfesionalDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getProfesionalesJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;

?>