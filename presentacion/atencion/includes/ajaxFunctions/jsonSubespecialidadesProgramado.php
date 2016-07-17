<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

$especialidad=$_REQUEST['especialidad'];

$subespecialidades = new SubespecialidadDatabaseLinker();

$sbespecis = $subespecialidades->getSubspecialidadesConConsultoriosProgramadosActivos_json($especialidad);

echo json_encode($sbespecis);