<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Internar Nuevo</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../includes/plug-in/jqGrid_5.0.2/css/ui.jqgrid.css" />
    <!--NOTIFICACION -->
    <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-default.css" />
    <link rel="stylesheet" type="text/css" href="../includes/plug-in/notificacion/css/ns-style-attached.css" />
    <script src="../includes/plug-in/notificacion/js/modernizr.custom.js"></script>
    <!--/NOTIFICACION -->
    <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/i18n/grid.locale-es.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jqGrid_5.0.2/js/jquery.jqGrid.min.js" ></script>
    <script type="text/javascript" src="includes/js/new.js"></script>
</head>
<body>
    <!-- barra -->
    <div id="barra" >
        <!-- navegar -->
        <div id="barraImage" >
            <span style="font-size: 2em;" class="icon icon-about"></span>
        </div>
        <div id="navegar">
            &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Internacion</a>&nbsp;&gt;&nbsp;<a href="#">Internar Paciente</a>
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
        <br><br>
        <div>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Seleccione Paciente</a></li>
                    <li><a href="#tabs-2">Seleccione Especialidad</a></li>
                    <li><a href="#tabs-3">Confirmar Datos Turno</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contenedorTab" style="display: inline-block;">
                        <h2>Paso 1: Seleccionar el paciente</h2>
                        <p>Busque el paciente para el cual desea internar por numero de documento, nombre, apellido y fecha de nacimiento. Si no lo encuentra podra crear uno nuevo luego de buscarlo.</p>
                        <br>
                        <div style="float:left; word-break: break-all;">
                            <form id="buscarPorNum">
                                <h4>Buscar Paciente</h4>
                                <select id="tipodoc" name="tipodoc" >
                                  <?php
                                  $td = $gen->getTiposDocumentos();
                                  for ($i=0; $i < count($td); $i++) { 
                                    echo "<option value=".$td[$i]['id'].">".$td[$i]['detalle_corto']."</option>";
                                  }
                                  ?>
                                </select><br>
                                <input type="text" name="nrodoc" id="nrodoc" placeholder="Nro Documento"/>
                                <button id="buscarxnum">Buscar</button>
                            </form>
                            <div id="miCargando" style="display:none;">
                                <p align="center">
                                    <img id="loading" src="../includes/images/loader.gif" alt="Cargando..."/>
                                    Espere mientras carga
                                </p>
                            </div>
                        </div>
                        <div id="fichaPaciente" style="display:none; float:right;">

                        </div>
                    </div>
                </div>
                <div id="tabs-2"> 
                    <h2>Paso 2: Escribir Variables de ingreso</h2>
                    <p>Detalle los motivos por el cual el paciente ingresa a la internacion.</p>
                    <br>
                    <div align="center">
                       
                    </div>
                </div>
                <div id="tabs-3">
                    <h2>Paso 3: Seleccione Sector Fisico y Cama</h2>
                    <p>Seleccione ubicacion donde el paciente va a permanecer</p>
                     <form>
                        <h4>Sector</h4>
                        <select id="sector" name="sector"  onchange="ingresandoSector();" >
                            <option value="">Seleccione</option>
                            <?php
                            $lista = $especialidades->getEspecialidadesConConsultoriosDeDemandaActivos();
                            for ($i=0; $i < count($lista); $i++){ 
                                echo "<option value=".$lista[$i]->getId().">".$lista[$i]->getDetalle()."</option>";
                            }
                            ?>
                        </select><br>
                        <h4>Camas Libres</h4>
                        <select id="camas" name="camas" >

                        </select>
                    </form>
                   
                </div>
                <div id="tabs-4">
                     <br><br>
                    <p><b>Paciente: </b><a id="nombrePaciente"></a></p>
                    <p><b>Numero Documento: </b><a id="nrodocPaciente"></a></p>
                    <p><b>Sector: </b><a id="sectorAcept"></a></p>
                    <p><b>Cama: </b><a id="camaAcept"></a></p>
                    <div align="center">
                        <button class="button-secondary" id="cargarInternacion">Internar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog" title="Estado Internacion">
        <p><b>Paciente Internado</b></p><br>

        Los datos de la internacion son los siguientes:<br>
        Tipo: INTERNACION<br>
        <p><b>Paciente: </b><a id="nombreDialog"></a></p>
        <p><b>Numero Documento: </b><a id="nrodocDialog"></a></p>
        <p><b>Sector: </b><a id="sectorDialog"></a></p>
        <p><b>Cama: </b><a id="camaDialog"></a></p>
    </div>

    <input type="hidden" id="idusuario" name="idusuario" value=<?php echo $data->getId();?> >
    <!--NOTIFICACION -->
    <script src="../includes/plug-in/notificacion/js/classie.js"></script>
    <script src="../includes/plug-in/notificacion/js/notificationFx.js"></script>
    <!--/NOTIFICACION -->
</body>
</html>