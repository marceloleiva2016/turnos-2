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

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$gen = new GeneralesDatabaseLinker();

//Tipos de consultorios
$consulDb = new ConsultorioDatabaseLinker();
$ListaTiposConsultorios = $consulDb->getTiposConsultorios();

//Profesionales
$profDb = new ProfesionalDatabaseLinker();
$Listaprofesionales = json_decode($profDb->getProfesionalesJson(1, 200, array()));
$profesionalesRows = $Listaprofesionales->rows;

//Especialidades
$espDb = new EspecialidadDatabaseLinker();
$ListaEspecialidades = $espDb->getEspecialidades();
$especialidadesRows = $ListaEspecialidades->data;

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
  <script type="text/javascript" src="includes/js/new.js" ></script>
  
</head>
<body>
<!-- barra -->
  <div id="barra" >
    <!-- navegar -->
    <div id="barraImage" >
      <span style="font-size: 2em;" class="icon icon-about"></span>
    </div>
    <div id="navegar">
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Consultorios</a>&nbsp;&gt;&nbsp;<a href="#">Nuevo</a>
    </div>
    <!-- /navegar-->
    <!-- usuario -->
    <div id="usuario">
        <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
    </div>
    <!-- /usuario-->
  </div>
  <!-- /barra -->
  <div id="container" align="center">
    <form id="consultorioForm" post="includes/ajaxfunctions/guardarConsultorio.php">
      <fieldset>
        <legend>Tipo consultorio</legend>
        <select name="tipo_consultorio" id="tipo_consultorio" onchange="seleccionadoTipoConsul(this);">
            <?php
              for ($i=0; $i < count($ListaTiposConsultorios); $i++) { 
                echo "<option value=".$ListaTiposConsultorios[$i]['id'].">".$ListaTiposConsultorios[$i]['detalle']."</option>";
              }
            ?>
        </select>
      </fieldset>

      <fieldset>
        <legend>Especialidad</legend>
        <select name="especialidad" id="especialidad" onchange="seleccionadoEspeci(this);" >
          <option value="">Seleccione</option>
          <?php
            for ($i=0; $i < count($especialidadesRows); $i++) {
              echo "<option value=".$especialidadesRows[$i]->id.">".$especialidadesRows[$i]->detalle."</option>";
            }
          ?>
        </select>
      </fieldset>

      <fieldset>
        <legend>Subespecialidad</legend>
        <select name="subespecialidad" id="subespecialidad" >
        </select>
      </fieldset>

      <fieldset>
        <legend>Profesional</legend>
        <select name="profesional" id="profesional">
          <option value="">Seleccione</option>
          <?php
          for ($i=0; $i < count($profesionalesRows); $i++) {
            echo "<option value=".$profesionalesRows[$i]->cell[0].">".Utils::phpStringToHTML($profesionalesRows[$i]->cell[1])." ".Utils::phpStringToHTML($profesionalesRows[$i]->cell[2])."</option>";
          }
          ?>
        </select>
      </fieldset>

      <fieldset>
        <legend>Comienzo / Finalizacion</legend>
        Desde:<input name="comienzo" id="comienzo" type="text" style="width:90px;">
        Hasta:<input name="finalizacion" id="finalizacion" type="text" style="width:90px;">
      </fieldset>

      <div id="divTipoConsultorio" >

        <fieldset>
          <legend>Dias Anticipacion</legend>
          <select name="dias_anticipacion" id="dias_anticipacion">
            <option value="30">30</option>
            <option value="60">60</option>
            <option value="90">90</option>
            <option value="120">120</option>
            <option value="150">150</option>
            <option value="180">180</option>
          </select>
        </fieldset>

        <fieldset>
          <legend>Duracion Turno</legend>
          <input name="intervalo_minutos" id="intervalo_minutos" type="text" style="width:30px;">min
        </fieldset>

        <fieldset>
          <legend>Feriados</legend>
          SI:<input type="radio" name="feriados" value="true" checked>&nbsp;&nbsp;NO:<input type="radio" name="feriados" value="false">
        </fieldset>

        </div>
        <input type="submit" class="button-secondary" value="Guardar" id="guardarConsultorio">
    </form>
</body>
</html>