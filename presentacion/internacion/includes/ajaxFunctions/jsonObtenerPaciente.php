<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'pacienteDatabaseLinker.class.php';

$DBpac = new PacienteDataBaseLinker();

$tipodoc = $_POST['tipoDoc'];

$nrodoc = $_POST['nroDoc'];

$return = new stdClass();

$return->ret = false;

$pac = $DBpac->getDatosPacientePorNumero($tipodoc, $nrodoc);

if($pac->getNrodoc()!=null)
{
    $return->nombre = $pac->getNombre()." ".$pac->getApellido();

    $return->nrodoc = $pac->getNrodoc();

    $return->ret = true;
}

echo json_encode($return);