<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$id = $_REQUEST['idturno'];

$dbTurnero = new turneroDatabaseLinker();

$puedeLlamar = $dbTurnero->puedeLlamar($id);

if($puedeLlamar) {

    $ret = $dbTurnero->insertarLlamado($id, $usuarioUnset->getId());

} else {

    $ret = new stdClass();
    $ret->message = "Para volver a llamar al paciente debes esperar al menos 10 min!";
    $ret->result = true;

}

echo json_encode($ret);
?>