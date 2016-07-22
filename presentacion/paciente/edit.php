<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once negocio.'paciente.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';

session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$tipodoc = 1;//$_REQUEST['tipodoc'];
$nrodoc = 2548789;//$_REQUEST['nrodoc'];

$gen = new GeneralesDatabaseLinker();
$pacDB = new PacienteDatabaseLinker();

$paciente = $pacDB->getDatosPacientePorNumero($tipodoc, $nrodoc);

var_dump($paciente);

?>
<!DOCTYPE html>
<html>
<head>
  <title>Paciente</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
  <script type="text/javascript" src="includes/js/edit.js" ></script>
  <script type="text/javascript">
    var pais = <?php  echo $paciente->idpais; ?>;
    var provincia = <?php  echo $paciente->idprovincia; ?>;
    var partido = <?php  echo $paciente->idpartido; ?>;
    var localidad = <?php  echo $paciente->idlocalidad; ?>;

  $(document).ready(function() {
    $('#pais option[value=<?php  echo $paciente->idpais; ?>]').prop('selected', 'selected').change();
    $('#provincia option[value=<?php  echo $paciente->idprovincia; ?>]').prop('selected', 'selected').change();
    $('#partido option[value=<?php  echo $paciente->idpartido; ?>]').prop('selected', 'selected').change();
    $('#localidad option[value=<?php  echo $paciente->idlocalidad; ?>]').prop('selected', 'selected').change();
  });
  </script>
</head>
<body>
<!-- barra -->
  <div id="barra" >
    <!-- navegar -->
    <div id="barraImage" >
        <span style="font-size: 2em;" class="icon icon-about"></span>
    </div>
    <div id="navegar">
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Editar Paciente</a>
    </div>
    <!-- /navegar-->
    <!-- usuario -->
    <div id="usuario">
        <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
    </div>
    <!-- /usuario-->
  </div>
    <!-- /barra -->
    <div id="container">
      <form id="formPaciente">
        <div align="center">
          <h3>Modificar Paciente</h3>
        </div>
        <div id="accordionPaciente">
          <h3>Datos Personales</h3>
            <div align="center" style="display: block; height: auto !important;">
              <label>Tipo y Numero Documento: </label><h2><?php echo $gen->getDescripcionTipoDocumento($paciente->tipodoc)['detalle_corto']."  ".$paciente->nrodoc;?></h2><br>
              <label>Nombre y Apellido : </label><input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $paciente->nombre; ?>" />
              <input type="text" name="apellido" id="apellido" placeholder="Apellido" value="<?php echo $paciente->apellido; ?>"/><br><br>
              <div id="radioset">
                <input type="radio" id="generom" name="sexo" value="M" <?php if($paciente->sexo=='M'){ echo "checked='checked'"; } ?> ><label for="generom">Masculino</label>
                <input type="radio" id="generof" name="sexo" value="F" <?php if($paciente->sexo=='F'){ echo "checked='checked'"; } ?> ><label for="generof">Femenino</label>
              </div><br>
              <label>Fecha Nacimiento :</label><input type="text" name="fecha_nac" id="fecha_nac" placeholder="Fecha Nacimiento" value="<?php echo Utils::sqlDateToHtmlDate($paciente->fecha_nac); ?>" /><br><br>
              <select id="donante" name="donante">
                <option value="">Es Donante?</option>
                <option value="0">NO</option>
                <option value="1">SI</option>
                <option value="2">NO SABE</option>
              </select>
            </div>
          <h3>Datos de Ubicacion</h3>
            <div align="center">
              <select id="pais" name="pais" onchange="ingresandoPais();">
                <option value="">Pais</option>
                <?php
                  $paises = $gen->getPaises();
                  for ($i=0; $i < count($paises); $i++) { 
                    echo "<option value=".$paises[$i]['id'].">".$paises[$i]['descripcion']."</option>";
                  }
                ?>
              </select>
              <select id="provincia" name="provincia" onchange="ingresandoProvincia();">
              </select>
              <select id="partido" name="partido" onchange="ingresandoPartido();">
              </select>
              <select id="localidad" name="localidad">
              </select><br><br>

              <input type="number" name="cp" id="cp" placeholder="Codigo Postal" />

              <input type="text" name="calle_nombre" id="calle_nombre" placeholder="Calle"/>

              <input type="number" name="calle_numero" id="calle_numero" placeholder="Numero" /><br><br>

              <input type="text" name="piso" id="piso" placeholder="Piso" />

              <input type="text" name="departamento" id="departamento" placeholder="Departamento"/><br><br>

            </div>

          <h3>Datos de Contacto</h3>
            <div align="center">

              <input type="number" name="telefono" id="telefono" placeholder="Telefono"/><br><br>

              <input type="number" name="telefono2" id="telefono2" placeholder="Telefono Alternativo"/><br><br>

              <input type="text" name="email" id="email" placeholder="Email"/><br><br>

              <input type="hidden" name="usuario" id="usuario" value='<?=$data->getId()?>'>

            </div>

        </div>
        <div align="center">
          <button id="guardar" style="height:50px; width:200px;">Guardar</button>
        </div>

      </form>

    </div>

</body>

</html>