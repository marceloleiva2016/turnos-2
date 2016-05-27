<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$gen = new GeneralesDatabaseLinker();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Consultorio</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
  <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
  <script type="text/javascript" src="includes/js/paciente.js" ></script>
  
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
  <div id="container" align="center">
    <form id="consultorioForm">
      <h1>Tipo consultorio</h1>
      <select>
        <option>Demanda</option>
        <option>Interno</option>
      </select>

      <h1>Dias Anticipacion</h1>
      <select>
        <option>30</option>
        <option>60</option>
        <option>90</option>
        <option>120</option>
        <option>150</option>
        <option>180</option>
      </select>

      <h1>Profesional</h1>
      <select>
        <option>Lucero Humberto</option>
      </select>

      <h1>Duracion en minutos</h1>
      <input type="text">

      <h1>Subespecialidad</h1>
      <select>
        <option>Cardiologia</option>
      </select>

      <h1>Dia de semana</h1>
      <select>
        <option>LUNES</option>
        <option>MERTES</option>
        <option>MIERCOLES</option>
        <option>JUEVES</option>
        <option>VIERNES</option>
        <option>SABADO</option>
        <option>DOMINGO</option>
      </select>

      <h1>Hora Desde</h1>
      <input type="text">

      <h1>Hora Hasta</h1>
      <input type="text">

    </form>
  </div>

</body>
</html>