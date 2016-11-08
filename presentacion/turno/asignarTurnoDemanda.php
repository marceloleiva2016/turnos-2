<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'especialidadDatabaseLinker.class.php';
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

$especialidades = new EspecialidadDatabaseLinker();

$gen = new GeneralesDatabaseLinker();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Asignar Turno Demanda</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.php' >
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
    <script type="text/javascript" src="../includes/plug-in/jqPrint/jquery.jqprint-0.3.js" ></script>
    <script type="text/javascript" src="includes/js/asignarTurnoDemanda.js" ></script>
</head>
<body>
    <!-- barra -->
    <div id="barra" >
        <!-- navegar -->
        <div id="barraImage" >
            <span style="font-size: 2em;" class="icon icon-about"></span>
        </div>
        <div id="navegar">
            &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Asignar Turno Demanda</a>
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
        <br>
        <div align="center">
            <h2>Asignar Turno Demanda</h2>
        </div>
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
                        <p>Busque el paciente para el cual desea crear el turno por numero de documento a, nombre, apellido y fecha de nacimiento. Si no lo encuentra podra crear uno nuevo luego de buscarlo.</p>
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
                                    <img id="loading" src="../includes/images/loader.gif" alt="Cargando..."/><br>
                                    Cargando Paciente
                                </p>
                            </div>
                            <div id="miCargandoOsoc" style="display:none;">
                                <p align="center">
                                    <img id="loading" src="../includes/images/loader.gif" alt="Cargando..."/><br>
                                    Buscando Obra social en linea
                                </p>
                            </div>
                        </div>
                        <div id="fichaOsoc" style="display:none; float:right;">

                        </div>

                        <div id="fichaPaciente" style="display:none; float:right;">

                        </div>
                    </div>
                </div>
                <div id="tabs-2"> 
                    <h2>Paso 2: Seleccionar especialidad y subespecialidades</h2>
                    <p>Seleccione una Especialidad y luego una Subespecialidad disponible con turnos por demanda.</p>
                    <br>
                    <div align="center">
                        <form>
                        <h4>Especialidad</h4>
                        <select id="especialidad" name="especialidad"  onchange="ingresandoEsp();" >
                            <option value="">Seleccione</option>
                            <?php
                            $lista = $especialidades->getEspecialidadesConConsultoriosDeDemandaActivos();
                            for ($i=0; $i < count($lista); $i++){ 
                                echo "<option value=".$lista[$i]->getId().">".$lista[$i]->getDetalle()."</option>";
                            }
                            ?>
                        </select><br>
                        <h4>Subspecialidad</h4>
                        <select id="subespecialidad" name="subespecialidad" >

                        </select>
                        </form>
                    </div>
                </div>
                <div id="tabs-3">
                    <h2>Paso 3: Confirmar los datos ingresados</h2>
                    <p>Verifique si los datos ingresados son correctos y luego presione ingresar para agregar el paciente a la lista de espera.</p>
                    <br><br>
                    <p><b>Paciente: </b><a id="nombrePaciente"></a></p>
                    <p><b>Numero Documento: </b><a id="nrodocPaciente"></a></p>
                    <p><b>Especialidad: </b><a id="especialidadAcept"></a></p>
                    <p><b>Subespecialidad: </b><a id="subespecialidadAcept"></a></p>
                    <div align="center">
                        <button class="button-secondary" id="cargarTurnoDemanda">Asignar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog" title="Estado Turno">
        <p><b>Turno Asignado</b></p><br>

        Los datos del turno son los siguientes:<br>
        Tipo Turno : DEMANDA<br>
        <p><b>Paciente: </b><a id="nombreDialog"></a></p>
        <p><b>Numero Documento: </b><a id="nrodocDialog"></a></p>
        <p><b>Especialidad: </b><a id="especialidadDialog"></a></p>
        <p><b>Subespecialidad: </b><a id="subespecialidadDialog"></a></p>
    </div>

    <input type="hidden" id="idusuario" name="idusuario" value=<?php echo $data->getId();?> >
    <!--NOTIFICACION -->
    <script src="../includes/plug-in/notificacion/js/classie.js"></script>
    <script src="../includes/plug-in/notificacion/js/notificationFx.js"></script>
    <!--/NOTIFICACION -->
</body>
</html>