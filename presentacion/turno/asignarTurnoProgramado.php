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
    <title>Asignar Turno Programado</title>
    <link media="screen" type='text/css' rel='stylesheet' href='../includes/css/demo.css' >
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
    <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    <script type="text/javascript" src="includes/js/asignarTurnoProgramado.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jqPrint/jquery.jqprint-0.3.js" ></script>
</head>
<body>
    <!-- barra -->
    <div id="barra" >
        <!-- navegar -->
        <div id="barraImage" >
            <span style="font-size: 2em;" class="icon icon-about"></span>
        </div>
        <div id="navegar">
            &nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Asignar Turno Programado</a>
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
                    <li><a href="#tabs-3">Seleccione Fecha y Horario</a></li>
                    <li><a href="#tabs-4">Confirme los datos</a></li>
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
                    <h2>Paso 2: Seleccionar especialidad y subespecialidades</h2>
                    <p>Seleccione una Especialidad y luego una Subespecialidad disponible con turnos por demanda.</p>
                    <br>
                    <div align="center">
                        <form>
                        <h4>Especialidad</h4>
                        <select id="especialidad" name="especialidad"  onchange="ingresandoEsp();" >
                            <option value="">Seleccione</option>
                            <?php
                            $lista = $especialidades->getEspecialidadesConConsultoriosProgramadosActivos();
                            for ($i=0; $i < count($lista); $i++){ 
                                echo "<option value=".$lista[$i]->getId().">".$lista[$i]->getDetalle()."</option>";
                            }
                            ?>
                        </select><br>
                        <h4>Subspecialidad</h4>
                        <select id="subespecialidad" name="subespecialidad" onchange="ingresandoSubEsp();" >

                        </select>
                         <h4>Profesional</h4>
                        <select id="profesional" name="profesional" onchange="ingresandoProf();" >

                        </select>
                        </form>
                    </div>
                </div>
                <div id="tabs-3" class="dias">
                    <div id="fechasLoading" style="display:none;">
                        <p align="center">
                            <img id="loading" src="../includes/images/loader.gif" alt="Cargando..."/>
                            Espere mientras carga
                        </p>
                    </div>
                    <div id="fechasCargador" style="display:none;"></div>

                </div>
                <div id="tabs-4">
                    <h2>Paso 3: Confirmar los datos ingresados</h2>
                    <p>Verifique si los datos ingresados son correctos y luego presione ingresar para agregar el paciente a la lista de espera.</p>
                    <br><br>
                    <p><b>Paciente: </b><a id="nombrePaciente"></a></p>
                    <p><b>Numero Documento: </b><a id="nrodocPaciente"></a></p>
                    <p><b>Especialidad: </b><a id="especialidadAcept"></a></p>
                    <p><b>Subespecialidad: </b><a id="subespecialidadAcept"></a></p>
                    <p><b>Profesional: </b><a id="profesionalAcept"></a></p>
                    <p><b>Fecha: </b><a id="fechaAcept"></a></p>
                    <p><b>Hora: </b><a id="horaAcept"></a></p>
                    <div align="center">
                        <button class="button-secondary" id="cargarTurnoProgramado">Asignar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog" title="Estado Turno">
        <p><b>Turno Asignado</b></p><br>

        Los datos del turno son los siguientes:<br>
        Tipo Turno : PROGRAMADO<br>
        <p><b>Paciente: </b><a id="nombreDialog"></a></p>
        <p><b>Numero Documento: </b><a id="nrodocDialog"></a></p>
        <p><b>Especialidad: </b><a id="especialidadDialog"></a></p>
        <p><b>Subespecialidad: </b><a id="subespecialidadDialog"></a></p>
        <p><b>Profesional: </b><a id="profesionalDialog"></a></p>
        <p><b>Fecha: </b><a id="fechaDialog"></a></p>
        <p><b>Hora: </b><a id="horaDialog"></a></p>
    </div>

    <div id="dialogHora"> 
    </div>

    <input type="hidden" id="idusuario" name="idusuario" value=<?php echo $data->getId();?> >
</body>
</html>