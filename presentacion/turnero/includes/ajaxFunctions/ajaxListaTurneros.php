<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';

$dbTurnero = new TurneroDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $dbTurnero->getTurnerosJson($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $ret;
?>
