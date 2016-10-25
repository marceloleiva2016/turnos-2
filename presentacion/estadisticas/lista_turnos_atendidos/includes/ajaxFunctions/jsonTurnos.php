<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$anio = $_REQUEST['anio'];

$mes = $_REQUEST['mes'];

$dbnt = new TurnoDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$int = $dbnt->getTurnosAtendidosTodosJson($anio, $mes ,$_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo $int;