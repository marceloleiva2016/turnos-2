<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacion.class.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';
session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
$idAtencion = $_REQUEST['id'];

$dbFormInt = new FormInternacionDatabaseLinker();
$dbInt = new InternacionDatabaseLinker();
$dbAtencion = new AtencionDatabaseLinker();
$dbGen = new GeneralesDatabaseLinker();

$idInternacion = $dbAtencion->obtenerIdTurno($idAtencion);
$InternacionPaciente = $dbInt->getInternado($idInternacion);
$partido = $dbGen->getPartido($InternacionPaciente->getPaciente()->getPais(), $InternacionPaciente->getPaciente()->getProvincia(), $InternacionPaciente->getPaciente()->getPartido());
$localidad = $dbGen->getLocalidad($InternacionPaciente->getPaciente()->getPais(), $InternacionPaciente->getPaciente()->getProvincia(), $InternacionPaciente->getPaciente()->getPartido(), $InternacionPaciente->getPaciente()->getLocalidad());

$Internacion = $dbFormInt->obtenerFormulario($idAtencion,$data->getId());
$observaciones = $Internacion->getObservaciones();
$egreso = $Internacion->getEgreso();

$id = $Internacion->getId();
?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Formulario Internacion</title>
        <meta name="description" content="formulario para atencion en Internacion para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />

        <link rel="shortcut icon" href="/includes/img/malvinasargentinas.icon.png">
        <link type="text/css" rel="stylesheet" href="../includes/css/normalize.css" />
        <link type="text/css" rel="stylesheet" href="../includes/css/demo.css" />
        <link type="text/css" rel="stylesheet" href="../includes/css/component.php" />
        <link type="text/css" rel="stylesheet" href="../includes/css/content.css" />

        <link type="text/css" rel="stylesheet" href="../../includes/css/barra.php" />
        <link type="text/css" rel="stylesheet" href="../../includes/css/iconos.css" />
        
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />

        <link type="text/css" rel="stylesheet" href="includes/css/formularioInternacion.php" />
        <link type="text/css" rel="stylesheet" href="includes/css/formularioInternacionImprimir.css" media="print" />

<!--Scripts-->

        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>

        <script type="text/javascript" src="../includes/js/modernizr.custom.js"></script>
        
        <script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>

        <script type="text/javascript" src="includes/js/formularioInternacion.js"></script>

        <script>
            var id = <?=$id ?>;
            var tieneEgreso =
            <?php
            if($egreso->getId()!=NULL)
            {
                echo "true";
            }
            else
            {
                echo "false";
            };
            ?>;
        </script>
    <body>
        <!-- barra -->
        <div id="barra" >
            <!-- navegar -->
            <div id="barraImage" >
                <span style="font-size: 2em;" class="icon icon-about"></span>
            </div>
            <div id="navegar">
                &nbsp;&nbsp;&nbsp;<a href="../../menu/">Sistema</a>&nbsp;&gt;&nbsp;<a href="#">Formulario Internacion</a>
            </div>
            <!-- /navegar-->
            <!-- usuario -->
            <div id="usuario">
                <a href="../../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
        </div>
        <!-- /barra -->
        <div id="container" class="container">
            <section>
                <form>
                    <div class="mockup-content">
                        <div class="hoja">
                            <div class="apartado">
                                <fieldset>
                                    <legend>Datos Personales / Internacion</legend>
                                    <table id="tblFichaPaciente" class="hor-minimalist-b"  width="100%">
                                        <tr>
                                            <td><b>Fecha: </b><?php echo Utils::sqlDateTimeToHtmlDateTime($InternacionPaciente->getFecha_creacion()); ?></td>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td><b>Nombre: </b><?php echo $InternacionPaciente->getPaciente()->getNombre()." ".$InternacionPaciente->getPaciente()->getApellido(); ?></td>
                                            <td><b>TipoDoc: </b><?php  echo Utils::nombreCortoTipodoc($Internacion->getPaciente()->getTipodoc()); ?></td>
                                            <td><b>NroDoc: </b><?php echo $InternacionPaciente->getPaciente()->getNrodoc(); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Obra Social: </b><?php echo $InternacionPaciente->getObraSocial()['detalle']; ?></td>
                                            <td><b>Edad: </b><?php echo $InternacionPaciente->getPaciente()->getEdadActual()." Años";?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Domicilio: </b><?php echo $InternacionPaciente->getPaciente()->getCalleNombre()." ".$InternacionPaciente->getPaciente()->getCalleNumero()." - ".$localidad['descripcion']; ?></td>
                                            <td><b>Partido: </b><?php echo $partido['descripcion']; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Telefono: </b><?php echo $InternacionPaciente->getPaciente()->getTelefono()."   ".$InternacionPaciente->getPaciente()->getTelefono2(); ?></td>
                                            <td><b><span class="noImprimir"></span></b></td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="apartado">
                                &nbsp;<b>Motivo Ingreso:</b>&nbsp;<?php echo $InternacionPaciente->getMotivo_ingreso(); ?>
                                <br>
                            </div>
                            <div class="apartado">
                                <fieldset>
                                <legend>Sangre</legend>
                                <?php
                                    $tipo = TipoLaboratorio::SANGRE;
                                    $laboratorios = $Internacion->getEstudiosLaboratorioSangre();
                                    
                                    $elementosPorFila = 8;
                                    $elementosDibujados = 0;
                                    $i=0;
                                ?>
                                <table cellspacing="8px" style="width: 100%">
                                    <?php
                                        /* @var $labo Laboratorio  */
                                        foreach ($laboratorios as $labo) {  
                                            if($i%$elementosPorFila==0)
                                            {
                                                echo "<tr>";
                                            }
                                            //escribo datos 
                                            if($labo->esNumerico)
                                            {
                                                echo '<td title="' . Utils::phpStringToHTML($labo->descripcion) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
                                            }
                                            else
                                            {
                                                echo '<td title="' . Utils::phpStringToHTML($labo->valor) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>'; 
                                            }
                                            
                                                echo "<b>". $labo->nombre . "</b><br />";
                                                if($labo->esNumerico)
                                                {
                                                    echo $labo->valor;
                                                }
                                                else
                                                {
                                                    echo Utils::phpStringToHTML($labo->valor);
                                                }
                                            echo "</td>";
                                            
                                            
                                            if ($i%$elementosPorFila==7)
                                            {
                                                echo "</tr>";   
                                            }
                                            $i++;
                                        }
                                        //Si tengo que cerrar la ultima fila
                                        if($i%$elementosPorFila!=0)
                                        {
                                            //echo '</tr>';
                                            echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'"></td>';
                                            echo '</tr>';
                                        } 
                                    ?>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="apartado">
                                <fieldset>
                                <legend>Orina</legend>
                                    
                                    <?php
                                    $laboratorios = $Internacion->getEstudiosLaboratorioOrina();
                                    
                                    $elementosPorFila = 7;
                                    $elementosDibujados = 0;
                                    $i=0;
                                    ?>
                                    
                                    <table cellspacing="8px" style="width: 100%">
                                        <?php
                                            /* @var $labo Laboratorio */
                                            foreach ($laboratorios as $labo) {  
                                                if($i%$elementosPorFila==0)
                                                {
                                                    echo "<tr>";
                                                }
                                                //escribo datos 
                                                if($labo->esNumerico)
                                                {
                                                    echo '<td title="' . Utils::phpStringToHTML($labo->descripcion) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
                                                }
                                                else
                                                {
                                                    echo '<td title="' . Utils::phpStringToHTML($labo->valor) . '" style="width:'.((int)(100/$elementosPorFila)).'% " nowrap>';
                                                }
                                                
                                                    echo "<b>". $labo->nombre . "</b><br />";
                                                    if($labo->esNumerico)
                                                    {
                                                        echo $labo->valor;
                                                    }
                                                    else
                                                    {
                                                        echo Utils::phpStringToHTML($labo->valor);
                                                    }
                                                echo "</td>";
                                                
                                                
                                                if ($i%$elementosPorFila==7)
                                                {
                                                    echo "</tr>";   
                                                }
                                                $i++;
                                            }
                        
                                            //Si tengo que cerrar la ultima fila
                                            if($i%$elementosPorFila!=0)
                                            {
                                                //echo '</tr>';
                                                
                                                echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'">&nbsp;</td>';
                                                echo '</tr>';
                                                
                                            }
                                        ?>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="apartado">
                                <fieldset>
                                <legend>Estudios</legend>
                                <div id="divRayos" style="float:left; padding: 0.2em; width: 60%">
                                    <table cellspacing="8px" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 120px; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Rx</th>
                                        <th style="width: 50px; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
                                        <th style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Observaciones</th>
                                    </tr>
                                    </thead>
                                        <?php
                                            /* @var $estudio Rayo*/
                                            foreach ($Internacion->getRayos() as $estudio) {
                                                echo '<tr>';
                                                echo '<td title="' . $estudio->descripcion . '" nowrap>';
                                                    echo "<b>". $estudio->nombre . "</b>";
                                                echo "</td>";
                                                echo '<td title="' . $estudio->descripcion . '">';
                                                    echo ($estudio->valor?"Si":"No");
                                                echo "</td>";
                                                echo '<td title="' . $estudio->descripcion . '">';
                                                    echo Utils::phpStringToHTML($estudio->observacion);
                                                echo "</td>";
                                                echo "</tr>";   
                                            }
                                        ?>
                                    </table>
                                </div>
                                <div id="divAltaComplejidad" style="float:left; padding: 0.2em; width: 40%">
                                    <table cellspacing="8px" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 85%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Alta Complejidad</th>
                                        <th style="width: 15%; border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Si/No</th>
                                    </tr>
                                    </thead> 
                                        <?php
                                            /* @var $estudio AltaComplejidad*/
                                            foreach ($Internacion->getAltaComplejidad() as $estudio) {
                                                echo '<tr>';
                                                echo '<td title="' . $estudio->descripcion . '" nowrap>';
                                                    echo "<b>". $estudio->nombre . "</b>";
                                                echo "</td>";
                                                echo '<td title="' . $estudio->descripcion . '">';
                                                    echo ($estudio->valor?"Si":"No");
                                                echo "</td>";
                                                echo "</tr>";   
                                            }
                                        ?>
                                    </table>
                                </div>
                                </fieldset>
                            </div>
                            <?php
                            for ($q=0; $q < count($observaciones); $q++)
                            {
                            ?>
                            <div class="apartado">
                                <fieldset>
                                    <legend><?=$observaciones[$q]->getDetalle();?></legend>
                                    <div class="lstTextos">
                                        <?php
                                        $items = $observaciones[$q]->getitemsObservacion();

                                        for ($y=0; $y < count($items); $y++)
                                        {
                                            $title = "<b>Fecha:</b>" .$items[$y]->getFecha()."<br/>";
                                            $title .= "<b>Usuario:</b>" .$items[$y]->getUsuario()."<br/>";
                                            echo "<li class='tool' title='".$title."'>";
                                            echo "<span class=\" context-menu-one box menu-1\" obsId=\"".$items[$y]->getId()."\" obsUsr=\"".$items[$y]->getUsuario()."\">\n";
                                                
                                            print $items[$y]->getDetalle();
                                                
                                            echo "</span>";
                                            if($Internacion->getEgreso()==null)
                                            {
                                                echo "<input class='btnEditar' type='button' onclick='javascripst:editarObservacion(".$items[$y]->getId().");' value='editar' style='height:22px;width:49px;'>";
                                            }
                                            echo "</li>";
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                            <?php
                            }

                            if($egreso->getId()!=NULL)
                            {
                            ?>
                            <div class="apartado">
                                <fieldset>
                                    <legend>Egreso</legend>
                                    <div align="left">

                                        <b>DIAGNOSTICO:</b>
                                        <?=$egreso->getDiagnostico();?><br>
                                        <b>DESTINO:</b>
                                        <?=$egreso->getTipoEgreso();?><br>
                                        <b>FECHA:</b>
                                        <?=Utils::sqlDateToHtmlDate($egreso->getFechaCreacion());?><br>
                                        <b>PROFESIONAL:</b>
                                        <?=$egreso->getUsuario();?>
                                    </div>
                                </fieldset>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </section>
        </div><!-- /container -->
        <div id="menuMovible" class="morph-button morph-button-sidebar morph-button-fixed active open scroll">
            <button type="button"><span class="icon icon-menu"></span></button>
            <div class="morph-content">
                <div>
                    <div class="content-style-sidebar">
                        <span class="icon icon-delete"></span>
                        <h2>Menú ingreso</h2>
                        <ul>
                            <li><a class="icon icon-share" href="#" id="agrLaboratorios">Laboratorios</a></li>
                            <li><a class="icon icon-flash" href="#" id="agrRayos">Rayos</a></li>
                            <li><a class="icon icon-desktop" href="#" id="agrAltaComplejidad">Alta Complejidad</a></li>
                            <li><a class="icon icon-pentool" href="#" id="agrObservacion">Observacion/Tratamientos</a></li>
                            <li><a class="icon icon-hand" href="#" id="agrInterconsultas">Interconsultas</a></li>
                            <li><a class="icon icon-notepad" href="#" id="agrPendientes">Pendientes</a></li>
                            <li><a class="icon icon-exit" href="#" id="agrEgreso">Egreso</a></li>
                            <li><a class="icon icon-printer" href="#" id="imprimir">Imprimir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- morph-button -->
        <div id="dialogForm" style="display: none;"></div>
        <script src="../includes/js/classie.js"></script>
        <script src="../includes/js/uiMorphingButton_fixed.js"></script>
        <script>
            (function() {
                var docElem = window.document.documentElement, didScroll, scrollPosition,
                    container = document.getElementById( 'container' );

                // trick to prevent scrolling when opening/closing button
                function noScrollFn() {
                    window.scrollTo( scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0 );
                }

                function noScroll() {
                    window.removeEventListener( 'scroll', scrollHandler );
                    window.addEventListener( 'scroll', noScrollFn );
                }

                function scrollFn() {
                    window.addEventListener( 'scroll', scrollHandler );
                }

                function canScroll() {
                    window.removeEventListener( 'scroll', noScrollFn );
                    scrollFn();
                }

                function scrollHandler() {
                    if( !didScroll ) {
                        didScroll = true;
                        setTimeout( function() { scrollPage(); }, 60 );
                    }
                };

                function scrollPage() {
                    scrollPosition = { x : window.pageXOffset || docElem.scrollLeft, y : window.pageYOffset || docElem.scrollTop };
                    didScroll = false;
                };

                scrollFn();
                
                var el = document.querySelector( '.morph-button' );
                
                new UIMorphingButton( el, {
                    closeEl : '.icon-delete',
                    onBeforeOpen : function() {
                        // don't allow to scroll
                        noScroll();
                        // push main container
                        classie.addClass( container, 'pushed' );
                    },
                    onAfterOpen : function() {
                        // can scroll again
                        canScroll();
                        // add scroll class to main el
                        classie.addClass( el, 'scroll' );
                    },
                    onBeforeClose : function() {
                        // remove scroll class from main el
                        classie.removeClass( el, 'scroll' );
                        // don't allow to scroll
                        noScroll();
                        // push back main container
                        classie.removeClass( container, 'pushed' );
                    },
                    onAfterClose : function() {
                        // can scroll again
                        canScroll();
                    }
                } );
            })();
        </script>

        <form action="index.php" method="post" id="formInternacion">
            <input type="hidden" name="id" value="<?php echo $idAtencion; ?>">
        </form>
    </body>
</html>