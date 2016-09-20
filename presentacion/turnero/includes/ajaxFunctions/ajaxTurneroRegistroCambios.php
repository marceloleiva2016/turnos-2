<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';

$idturnero = $_REQUEST['id'];

$turno_previo = $_REQUEST['idllamado'];

$cantidad  = $_REQUEST['cantidad'];

$dbTurnero = new TurneroDatabaseLinker();

$dbTurnero->darDeBajaTurnosCaducados(5);

$response = $dbTurnero->hayCambiosEnLlamador($idturnero, $turno_previo, $cantidad);

echo json_encode($response);
?>