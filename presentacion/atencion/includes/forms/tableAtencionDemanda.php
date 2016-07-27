<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once datos.'turnoDatabaseLinker.class.php';

$dbTurno = new TurnoDatabaseLinker();

$idsubespecialidad = $_REQUEST['subespecialidad'];

if($idsubespecialidad==null OR !isset($idsubespecialidad)) {
    echo "<br><br><br><br>No se pudo consultar los pacientes sin obtener una subespecialidad";
    die();
}


$turnos = $dbTurno->getTurnosConfirmadosDemanda($idsubespecialidad);    

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
    echo "<tr><td>".$turnos[$i]['fecha']."</td><td id='tipodoc'>".$turnos[$i]['tipodoc']."</td><td id='nrodoc'>".$turnos[$i]['nrodoc']."</td><td>".$turnos[$i]['nombre']."</td><td><input type='button' class='button-secondary' value='ATENDER' onclick=javascript:mostrarFormulario('".$turnos[$i]['id']."');></td></tr>";
  }
  ?>
</table>