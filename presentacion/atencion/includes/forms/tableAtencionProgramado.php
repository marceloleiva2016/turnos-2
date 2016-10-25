<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'turneroDatabaseLinker.class.php';

$dbTurno = new TurnoDatabaseLinker();
$dbTurnero = new turneroDatabaseLinker();

$idsubespecialidad = $_REQUEST['subespecialidad'];
$idprofesional = $_REQUEST['profesional'];

if($idsubespecialidad==null OR !isset($idsubespecialidad)) {
    echo "<br><br><br><br>No se pudo consultar los pacientes sin obtener una subespecialidad";
    die();
} else if($idprofesional==null OR !isset($idprofesional)) {
    echo "<br><br><br><br>No se pudo consultar los pacientes sin obtener un profesional";
    die();
}
session_start();

if(!isset($_SESSION['usuario']))
{
    echo "No se encontro ningun usuario registrado. Actualice la pagina por favor.";
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$existe = $dbTurnero->existeConsultorioEnTurnero2($idsubespecialidad, $idprofesional);

$turnos = $dbTurno->getTurnosConfirmadosConsultorio($idsubespecialidad, $idprofesional);

$caducar = $dbTurno->caducarTurnosProgramados($idsubespecialidad, $idprofesional, $data->getId());

if(count($turnos)==0) {
    echo "<br><br><br><br>Sin pacientes en espera";
    die();
}

?>
<br>
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
   if($existe){
      $botonLlamar = "&nbsp;<button class='progress-button' data-style='shrink' onclick=javascript:llamarPaciente('".$turnos[$i]['id']."'); data-perspective data-horizontal>Llamar</button>";
    } else {
      $botonLlamar = "";
    }
    
    echo "<tr><td>".$turnos[$i]['fecha']."</td><td id='tipodoc'>".$turnos[$i]['tipodoc']."</td><td id='nrodoc'>".$turnos[$i]['nrodoc']."</td><td>".$turnos[$i]['nombre']."</td>
    <td><input type='button' class='button-secondary' value='ATENDER' onclick=javascript:mostrarFormulario('".$turnos[$i]['id']."');>".$botonLlamar."</td></tr>";
  }
  ?>
</table>
<script>
  [].slice.call( document.querySelectorAll( 'button.progress-button' ) ).forEach( function( bttn ) {
    new ProgressButton( bttn, {
      callback : function( instance ) {
        var progress = 0,
          interval = setInterval( function() {
            progress = Math.min( progress + Math.random() * 0.1, 1 );
            instance._setProgress( progress );

            if( progress === 1 ) {
              instance._stop(1);
              clearInterval( interval );
            }
          }, 200 );
      }
    } );
  } );
</script>