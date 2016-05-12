<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$db = new TurnoDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $db->getTurnosConfirmadosJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;

?>