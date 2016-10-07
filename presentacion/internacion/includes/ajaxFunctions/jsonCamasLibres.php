<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'camaDatabaseLinker.class.php';

$sector=$_REQUEST['sector'];

$Camas = new CamaDatabaseLinker();

$camas = $Camas->getCamasLibresEnSector($sector);

echo json_encode($camas);