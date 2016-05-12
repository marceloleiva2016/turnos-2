<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'estadisticaDatabaseLinker.class.php';

$db = new EstadisticaDatabaseLinker();

/*$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}
*/
$ret = $db->turnosAntendidosYporAntenderDemanda(4,2016);//($_REQUEST['page'], $_REQUEST['rows'], $arr);
    
echo json_encode($ret);

?>