<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';

$dbTurnero = new TurneroDatabaseLinker();

$arr= array();

$idturnero = $_REQUEST['idturnero'];

$ret = $dbTurnero->getConsultoriosEnTurneroJson($idturnero,$_REQUEST['page'], $_REQUEST['rows']);
    
echo $ret;
?>
