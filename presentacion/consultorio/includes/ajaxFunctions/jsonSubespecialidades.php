<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$gnls = new  SubespecialidadDatabaseLinker();

$esp = $_POST['especialidad'];

if($esp!=null AND !isset($esp))
{
    $std = null;
}
else
{
    $std = $gnls->getSubespecialidadesEnEspecialidad($esp);
}

echo json_encode($std);
?>