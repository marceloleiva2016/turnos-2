<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$consulDb = new ConsultorioDatabaseLinker();

$idhorario = $_REQUEST['id'];

$del = $consulDb->borrarHorario($idhorario);

$ret = new StdClass();

if(!$del) {
    $ret->result = false;
    $ret->message = "Ocurrio un error al borrar el horario!";
} else {
    $ret->result = true;
    $ret->message = "Horario borrado correctamente!";
}

echo json_encode($ret);
?>