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

$fecha_fin = Utils::sqlDateToHtmlDate($consultorio['fecha_fin']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Consultorio N&deg; <?php echo $id; ?></title>
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/demo.php">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.php">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="includes/css/css_consultorio.php">
  <!--NOTIFICACION -->
  <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-default.css" />
  <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-style-attached.css" />
  <script src="../includes/plug-in/notificacion/js/modernizr.custom.js"></script>
  <!--/NOTIFICACION -->
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
  <script type="text/javascript">
    var id=<?php echo $id; ?>;
    var tipo_consultorio=<?php echo $consultorio['idtipo_consultorio']; ?>;
    var activo=<?php echo $consultorio['habilitado']; ?>;
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
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema</a>&nbsp;&gt;&nbsp;<a href="index.php">Consultorios</a>
        &nbsp;&gt;&nbsp;<a href="#">Editar Consultorio</a>
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
  N&deg; <?php echo $id; ?>
  <div id="container" align="center">

      Tipo consultorio :
      <?php echo $consultorio['tipo_consultorio']; ?><br>

      Especialidad :
      <?php echo $consultorio['especialidad']; ?><br>

      Subespecialidad :
      <?php echo $consultorio['subespecialidad']; ?><br>

      Profesional :
      <?php echo $consultorio['profesional']; ?><br>

      Comienzo / Finalizacion :
      Desde:<?php echo Utils:: sqlDateToHtmlDate($consultorio['fecha_inicio'])."&nbsp;";
      if($fecha_fin!='NULL'){
        echo "Hasta:".Utils:: sqlDateToHtmlDate($consultorio['fecha_fin']);
      } else {
        echo "Hasta: Indefinido";
      }
      ?><br>

      <?php if($consultorio['idtipo_consultorio']) { ?>
        <div id="divTipoConsultorio" >
          Dias Anticipacion :
          <?php echo $consultorio['dias_anticipacion']; ?><br>

          Duracion Turno :
          <?php echo $consultorio['duracion']; ?>min<br>

          Feriados :
          <?php
          if($consultorio['feriado']=="1"){
            echo "SI";
          } else {
            echo "NO";
          } ?><br>

      </div>

      <?php } ?>

    <div id="cargarHorarios">
      <form id="horariosForm" >
        <fieldset>
          <legend>Agregar Horario</legend>
          <select name="dia_semana" id="dia_semana" >
              <option value="1">LUNES</option>
              <option value="2">MARTES</option>
              <option value="3">MIERCOLES</option>
              <option value="4">JUEVES</option>
              <option value="5">VIERNES</option>
              <option value="6">SABADO</option>
              <option value="7">DOMINGO</option>
            </select>
            Desde<input name="hd_horas" id="hd_horas" type="text" style="width:40px;">:<input name="hd_minutos" id="hd_minutos" type="text" style="width:40px;">
            Hasta<input name="hh_horas" id="hh_horas" type="text" style="width:40px;">:<input name="hh_minutos" id="hh_minutos" type="text" style="width:40px;">
            <input type="submit" class="button-secondary" id="submitHorario" value="Agregar">
        </fieldset>
      </form>
      <div id="horarios">
      </div>
    </div>

    
    <input class="button-secondary"  type="button" onclick=" location.href='new.php' " value="NUEVO CONSULTORIO" name="nuevo" />

    <div id="dialogBaja" style="display: none;">Esta seguro que desea efectuar la baja del consultorio actual?</div>
    <div id="dialogBajaHorario" style="display: none;"></div>
    <!--NOTIFICACION -->
    <script src="../includes/plug-in/notificacion/js/classie.js"></script>
    <script src="../includes/plug-in/notificacion/js/notificationFx.js"></script>
    <!--/NOTIFICACION -->
</body>
</html>