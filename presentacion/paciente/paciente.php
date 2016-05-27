<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
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

$gen = new GeneralesDatabaseLinker();

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
  
</head>
<body>
<!-- barra -->
  <div id="barra" >
      <!-- navegar -->
      <div id="barraImage" >
          <span style="font-size: 2em;" class="icon icon-about"></span>
      </div>
      <div id="navegar">
          &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Paciente</a>
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
          <h3>Paciente</h3>
        </div>
        <div id="accordionPaciente">
          <h3>Datos Personales</h3>
            <div align="center" style="display: block; height: auto !important;">
              <select id="tipodoc" name="tipodoc" >
              <option value="">Tipo Documento</option>
              <?php
              $td = $gen->getTiposDocumentos();
              for ($i=0; $i < count($td); $i++) { 
                echo "<option value=".$td[$i]['id'].">".$td[$i]['detalle_corto']."</option>";
              }
              ?>
              </select> <input type="number" name="nrodoc" id="nrodoc" placeholder="Numero" /><br><br>
              <input type="text" name="nombre" id="nombre" placeholder="Nombre" />
              <input type="text" name="apellido" id="apellido" placeholder="Apellido"/><br><br>
              <div id="radioset">
                <input type="radio" id="generom" name="sexo" value="M" checked="checked"><label for="generom">Masculino</label>
                <input type="radio" id="generof" name="sexo" value="F" ><label for="generof">Femenino</label>
              </div><br><br>
              <input type="text" name="fecha_nac" id="fecha_nac" placeholder="Fecha Nacimiento" onchange="calcularEdad();"/>
              <input type="number" name="edad" id="edad" placeholder="Edad" /><br><br>
              <select id="donante" name="donante">
                <option value="">Es Donante</option>
                <option value="1">SI</option>
                <option value="2">NO</option>
                <option value="3">NO SABE</option>
              </select>
            </div>
          <h3>Datos de Ubicacion</h3>
            <div align="center">

              <select id="pais" name="pais">
                <option value="">Pais</option>
              </select>
              <select id="provincia" name="provincia">
                <option value="">Provincia</option>
              </select>
              <select id="partido" name="partido">
                <option value="">Partido</option>
              </select>
              <select id="localidad" name="localidad">
                <option value="">Localidad</option>
              </select><br><br>

              <input type="number" name="cp" id="cp" placeholder="Codigo Postal" onchange="ingresandoCP();" />

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

  <script type="text/javascript" src="includes/js/paciente.js" ></script>

</body>

</html>