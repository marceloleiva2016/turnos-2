<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'consultorioDatabaseLinker.class.php';

$subespecialidad=$_REQUEST['subespecialidad'];

$subespecialidades = new ConsultorioDatabaseLinker();

$profSubEsp = $subespecialidades->getProfesionalesEnSubespecialidadConConsultorioActivo_json($subespecialidad);

echo json_encode($profSubEsp);