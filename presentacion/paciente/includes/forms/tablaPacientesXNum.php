<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'pacienteDatabaseLinker.class.php';
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

if(!isset($_REQUEST['tipoDoc']) OR !isset($_REQUEST['nroDoc']) OR $_REQUEST['tipoDoc']=="" OR $_REQUEST['nroDoc']=="")
{
  die();
}

$tipodoc = $_POST['tipoDoc'];

$nrodoc = $_POST['nroDoc'];

$pac = $DBpac->getDatosPacientePorNumero($tipodoc, $nrodoc);

if($pac->getNrodoc()!=null)
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
    <td><?php echo $pac->getTipoDoc(); ?></td>
    <td><?php echo $pac->getNrodoc(); ?></td>
    <td><?php echo $pac->getNombre()." ".$pac->getApellido(); ?></td>
    <td>
  <?php
    if ($data->tienePermiso('PACIENTE_MODIFICAR_DATOS')){
      echo "<input class='button-secondary'  type='button' onclick='location.href=\"edit.php?tipodoc=".$pac->getTipoDoc()."&nrodoc=".$pac->getNrodoc()."\" ' value='MODIFICAR DATOS' name='modificardatos' />";
    }

    if ($data->tienePermiso('CONSULTAR_HC')){
      echo "<input class='button-secondary'  type='button' onclick='location.href=\"../historial_clinico/index.php?tipodoc=".$pac->getTipoDoc()."&nrodoc=".$pac->getNrodoc()." \" ' value='CONSULTAR HC' name='consultarhc' />";
    }
  echo "</td></tr></table>";

} else {
  echo "No se encontraron pacientes";
}