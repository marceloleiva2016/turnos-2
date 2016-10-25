<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'camaDatabaseLinker.class.php';

$db = new CamaDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getCamasJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;
?>