<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'internacionDatabaseLinker.class.php';

$anio = $_REQUEST['anio'];

$mes = $_REQUEST['mes'];

$dbnt = new InternacionDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$int = $dbnt->getInternadosTodosJson($anio, $mes ,$_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $int;