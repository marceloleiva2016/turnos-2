<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$dbTurno = new TurnoDatabaseLinker();

$idsubespecialidad = $_REQUEST['subespecialidad'];
$idprofesional = $_REQUEST['profesional'];

if($idsubespecialidad==null OR !isset($idsubespecialidad)) {
    echo "<br><br><br><br>No se pudo consultar los pacientes sin obtener una subespecialidad";
    die();
} else if($idprofesional==null OR !isset($idprofesional)) {
    echo "<br><br><br><br>No se pudo consultar los pacientes sin obtener un profesional";
    die();
}


$turnos = $dbTurno->getTurnosConfirmadosConsultorio($idsubespecialidad, $idprofesional);    

if(count($turnos)==0) {
    echo "<br><br><br><br>Sin pacientes en espera";
    die();
}

?>
<br><br>
<table align="center" border="1" id="listado">
  <tr>
    <th>Hora</th>
    <th id="tipodoc">Tipo Doc</th>
    <th id="nrodoc">N&deg; Doc</th>
    <th>Paciente</th>
    <th>Accion</th>
  </tr>
  <?php
  for ($i=0; $i < count($turnos); $i++) { 
    echo "<tr><td>".$turnos[$i]['fecha']."</td><td id='tipodoc'>".$turnos[$i]['tipodoc']."</td><td id='nrodoc'>".$turnos[$i]['nrodoc']."</td><td>".$turnos[$i]['nombre']."</td><td><input style='height:22px; width:100px;' type='button' value='ATENDER' onclick=javascript:mostrarFormulario('".$turnos[$i]['id']."');></td></tr>";
  }
  ?>
</table>