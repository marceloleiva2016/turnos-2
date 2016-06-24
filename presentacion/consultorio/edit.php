<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'especialidadDatabaseLinker.class.php';
include_once negocio.'profesional.class.php';
include_once datos.'utils.php';

session_start();

if(!isset($_SESSION['usuario'])) {
  //echo "WHOOPSS, No se encontro ningun usuario registrado";
  header("Location: ../index.php?logout=1");
  
}

$id = $_REQUEST['id'];

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$consulDb = new ConsultorioDatabaseLinker();

$consultorio = $consulDb->getConsultorio($id);

var_dump($consultorio);

$fecha_fin = Utils::sqlDateToHtmlDate($consultorio['fecha_fin']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Consultorio</title>
  <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="includes/css/consultorio.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
  <script type="text/javascript">
    var id=<?php echo $id; ?>;
    var tipo_consultorio=<?php echo $consultorio['idtipo_consultorio']; ?>;
  </script>
  <script type="text/javascript" src="includes/js/edit.js" ></script>
</head>
<body>
<!-- barra -->
  <div id="barra" >
    <!-- navegar -->
    <div id="barraImage" >
      <span style="font-size: 2em;" class="icon icon-about"></span>
    </div>
    <div id="navegar">
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Consultorios</a>
    </div>
    <!-- /navegar-->
    <!-- usuario -->
    <div id="usuario">
        <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
    </div>
    <!-- /usuario-->
  </div>
  <!-- /barra -->
  <div class="topright">
    <input type="submit" class="button-secondary" value="Efectuar Baja" id="baja">
  </div>
  <div id="container" align="center">
    <form id="consultorioForm" post="includes/ajaxfunctions/guardarConsultorio.php">
      <fieldset>
        <legend>Tipo consultorio</legend>
        <?php echo $consultorio['tipo_consultorio']; ?>
      </fieldset>

      <fieldset>
        <legend>Especialidad</legend>
        <?php echo $consultorio['especialidad']; ?>
      </fieldset>

      <fieldset>
        <legend>Subespecialidad</legend>
        <?php echo $consultorio['subespecialidad']; ?>
      </fieldset>

      <fieldset>
        <legend>Profesional</legend>
        <?php echo $consultorio['profesional']; ?>
      </fieldset>

      <fieldset>
        <legend>Comienzo / Finalizacion</legend>
        Desde:<?php echo Utils:: sqlDateToHtmlDate($consultorio['fecha_inicio'])."&nbsp;";
        if($fecha_fin!='NULL'){
          echo "Hasta:".Utils:: sqlDateToHtmlDate($consultorio['fecha_fin']);
        } else {
          echo "Hasta: Indefinido";
        }
        ?>
      </fieldset>

      <?php if($consultorio['idtipo_consultorio']) { ?>
        <div id="divTipoConsultorio" >

        <fieldset>
          <legend>Dias Anticipacion</legend>
          <?php echo $consultorio['dias_anticipacion']; ?>
        </fieldset>

        <fieldset>
          <legend>Duracion Turno</legend>
          <?php echo $consultorio['duracion']; ?>min
        </fieldset>

        <fieldset>
          <legend>Feriados</legend>
          <?php
          if($consultorio['feriado']=="1"){
            echo "SI:<input type='radio' name='feriados' value='true' checked>&nbsp;&nbsp;NO:<input type='radio' name='feriados' value='false'>";
          } else {
            echo "SI:<input type='radio' name='feriados' value='true'>&nbsp;&nbsp;NO:<input type='radio' name='feriados' value='false' checked>";
          } ?>
        </fieldset>

      </div>

      <?php } ?>

    </form>

    <div id="cargarHorarios">
      <form id="consultorioForm" post="includes/ajaxfunctions/guardarHorario.php">
        <fieldset>
          <legend>Agregar Horario</legend>
          <select name="dia_semana" id="dia_semana" >
              <option value="1">LUNES</option>
              <option value="2">MERTES</option>
              <option value="3">MIERCOLES</option>
              <option value="4">JUEVES</option>
              <option value="5">VIERNES</option>
              <option value="6">SABADO</option>
              <option value="7">DOMINGO</option>
            </select>
            Desde<input  name="hd_horas" id="hd_horas" type="text" style="width:30px;">:<input  name="hd_minutos" id="hd_minutos" type="text" style="width:30px;">
            Hasta<input  name="hh_horas" id="hh_horas" type="text" style="width:30px;">:<input  name="hh_minutos" id="hh_minutos" type="text" style="width:30px;">
            <input type="submit" class="button-secondary" value="Agregar">
        </fieldset>
      </form>
      <div id="horarios">

      </div>
    </div>
    <div id="dialogBaja" style="display: none;">Esta seguro que desea efectuar la baja del consultorio actual?</div>
</body>
</html>