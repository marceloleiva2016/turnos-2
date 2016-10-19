<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'utils.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$DBpac = new PacienteDataBaseLinker();

if(!isset($_REQUEST['nombre']) OR $_REQUEST['nombre']=="")
{
  die();
}

$nombre = $_POST['nombre'];

$pacientes = $DBpac->getDatosPacientePorNombre($nombre);

if(count($pacientes)!=0)
{
?>
<br><br>
<table align="center" border="1" id="listado" class="tabla">
  <tr>
    <th id="tipodoc">Tipo Doc</th>
    <th id="nrodoc">N&deg; Doc</th>
    <th>Paciente</th>
    <th>Accion</th>
  </tr>
  <tr>
<?php
  for ($i=0; $i < count($pacientes); $i++) { 
?> 
  <td ><?php echo Utils::nombreCortoTipodoc($pacientes[$i]->getTipoDoc()); ?></td>
  <td ><?php echo $pacientes[$i]->getNrodoc(); ?></td>
  <td><?php echo $pacientes[$i]->getNombre()." ".$pacientes[$i]->getApellido(); ?></td>
  <td>
  <?php
    if ($data->tienePermiso('PACIENTE_MODIFICAR_DATOS')){
      echo "<input class='button-secondary'  type='button' onclick='location.href=\"edit.php?tipodoc=".$pacientes[$i]->getTipoDoc()."&nrodoc=".$pacientes[$i]->getNrodoc()."\" ' value='MODIFICAR DATOS' name='modificardatos' />";
    }

    if ($data->tienePermiso('CONSULTAR_HC')){
      echo "&nbsp;<input class='button-secondary'  type='button' onclick='location.href=\"../historial_clinico/index.php?tipodoc=".$pacientes[$i]->getTipoDoc()."&nrodoc=".$pacientes[$i]->getNrodoc()." \" ' value='CONSULTAR HC' name='consultarhc' />";
    }
  echo "</td></tr>";
  }

echo "</table>";

} else {
  echo "No se encontraron pacientes";
}