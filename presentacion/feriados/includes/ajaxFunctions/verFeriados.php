<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'feriadoDatabaseLinker.class.php';

$db = new FeriadoDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getFeriadosJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;

?>