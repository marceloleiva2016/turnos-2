<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacion.class.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';
session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
    die();
}

$dbInt = new InternacionDatabaseLinker();
$dbAtencion = new AtencionDatabaseLinker();

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
$idAtencion = $_REQUEST['id'];

$idInternacion = $dbAtencion->obtenerIdTurno($idAtencion);

$internacion = $dbInt->getInternado($idInternacion);

$diagnostico = $internacion->getDiagnostico();

echo "Fecha Ingreso: ".Utils::sqlDateTimeToHtmlDateTime($internacion->getFecha_creacion())."<br/>";
echo "Motivo Ingreso: ".$internacion->getMotivo_ingreso()."<br/>";
echo "Obra Social: ".$internacion->getObraSocial()['detalle']."<br/>";
echo "Diagnostico Ingreso: ".$diagnostico['codigo_completo']."->".$diagnostico['descripcion']."<br/>";

?>
