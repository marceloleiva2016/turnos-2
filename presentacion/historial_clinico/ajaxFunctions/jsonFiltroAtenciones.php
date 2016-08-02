<?php
include_once '../../../namespacesAdress.php';
include_once datos.'atencionDatabaseLinker.class.php';

$tipodoc = $_POST['tipoDoc'];
$nrodoc = $_POST['nroDoc'];
$esp = $_POST['especialidad'];

if($esp!=null AND !isset($esp))
{
    $std = null;
}
else
{
    $atencionDB = new AtencionDatabaseLinker();
    $atenciones = $atencionDB->getAtencionesEnPaciente($tipodoc, $nrodoc, $esp);
}

echo json_encode($atenciones);
?>