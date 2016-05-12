<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'estadisticaDatabaseLinker.class.php';

$db = new EstadisticaDatabaseLinker();

$ano = $_GET['ano'];

$mes = $_GET['mes'];

$ret = $db->especialidadesConSexosyRangos($mes,$ano);
    
echo json_encode($ret);
?>