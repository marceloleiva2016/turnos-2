<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$dbTurnos = new TurnoDatabaseLinker();

$dbTurnos->turnosToExcel();

?>