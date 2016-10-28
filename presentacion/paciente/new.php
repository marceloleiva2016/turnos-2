<?php
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
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

$obsocDB = new ObraSocialDatabaseLinker();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Crear Paciente</title>
	<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/demo.php">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.php">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
  <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
  <!--NOTIFICACION -->
  <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-default.css" />
  <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-style-attached.css" />
  <script src="../includes/plug-in/notificacion/js/modernizr.custom.js"></script>
  <!--/NOTIFICACION -->
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
        &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Nuevo Paciente</a>
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
          <h3>Nuevo Paciente</h3>
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
          </form>
          <h3>Datos de Obra Social</h3>
            <div align="center">
              <form id="formObraSocial">
                <fieldset>
                  <legend><b>Filtrar Obra Social:</b></legend>
                  <div>
                    Buscar:
                    <input name="det_busq" id="det_busq" placeholder="Detalle">
                    <button class="button-secondary" name="osocFiltrar" id="osocFiltrar" >Filtrar</button><br>
                    <select id="osoc" name="osoc">
                      <?php
                        $obrasSociales = $obsocDB->getObrasSocialesActivas();
                          for ($i=0; $i < count($obrasSociales); $i++) { 
                            echo "<option value=".$obrasSociales[$i]['id'].">".substr($obrasSociales[$i]['detalle'],0,80)."</option>";
                          }
                        ?>
                    </select>
                  </div>
                </fieldset>
                <fieldset>
                  <legend><b>Selecionar Obra Social:</b></legend>
                      <label>Nro de Afiliado :</label><input type="text" name="osoc_nro_afiliado" id="osoc_nro_afiliado" /><br>
                      <label>Empresa :</label><input type="text" name="osoc_empresa" id="osoc_empresa" /><br>
                      <label>Direccion :</label><input type="text" name="osoc_direccion" id="osoc_direccion" /><br>
                      <label>Fecha de emision :</label><input type="text" name="osoc_fecha_emision" id="osoc_fecha_emision" /><br>
                      <label>Fecha de vencimiento :</label><input type="text" name="osoc_fecha_vencimiento" id="osoc_fecha_vencimiento" /><br>
                      <input type="hidden" name="idusuario" id="idusuario" value='<?=$data->getId()?>'>
                </fieldset>
              </form>
            </div>
        </div>
        <div align="center">
          <button id="guardar" style="height:50px; width:200px;">Crear Paciente</button>
        </div>
    </div>
    <!--NOTIFICACION -->
    <script src="../includes/plug-in/notificacion/js/classie.js"></script>
    <script src="../includes/plug-in/notificacion/js/notificationFx.js"></script>
    <!--/NOTIFICACION -->
</body>

</html>