<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$consulDb = new ConsultorioDatabaseLinker();

$idconsultorio = $_REQUEST['id'];

$del = $consulDb->borrarConsultorio($idconsultorio);

$ret = new StdClass();

if(!$del) {
    $ret->result = false;
    $ret->message = "Ocurrio un error al eliminar consultorio!";
} else {
    $ret->result = true;
    $ret->message = "consultorio eliminado correctamente!";
}

echo json_encode($ret);
?>