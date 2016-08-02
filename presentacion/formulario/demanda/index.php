<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once neg_formulario.'demanda/demanda.class.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
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

$dbDemanda = new DemandaDatabaseLinker();

$demanda = $dbDemanda->obtenerFormulario($idAtencion,$data->getId());
$observaciones = $demanda->getObservaciones();
$egreso = $demanda->getEgreso();

$id = $demanda->getId();
?>
<!DOCTYPE html>
<html lang="en" class="no-js demo-7">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>Formulario Demanda</title>
        <meta name="description" content="formulario para atencion en demanda para cualquier especialidad" />
        <meta name="keywords" content="expanding button, morph, modal, fullscreen, transition, ui" />
        <meta name="author" content="Juan Ferreyra" />

        <link rel="shortcut icon" href="/includes/img/malvinasargentinas.icon.png">
        <link type="text/css" rel="stylesheet" href="../includes/css/normalize.css" />
        <link type="text/css" rel="stylesheet" href="../includes/css/demo.css" />
        <link type="text/css" rel="stylesheet" href="../includes/css/component.css" />
        <link type="text/css" rel="stylesheet" href="../includes/css/content.css" />

        <link type="text/css" rel="stylesheet" href="../../includes/css/barra.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/css/iconos.css" />
        
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />

        <link type="text/css" rel="stylesheet" href="includes/css/formularioDemanda.css" />
        <link type="text/css" rel="stylesheet" href="includes/css/formularioDemandaImprimir.css" media="print" />

<!--Scripts-->

        <script type="text/javascript" src="../../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
        <script type="text/javascript" src="../../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>

        <script type="text/javascript" src="../includes/js/modernizr.custom.js"></script>
        
        <script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>

        <script type="text/javascript" src="includes/js/formularioDemanda.js"></script>

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
                &nbsp;&nbsp;&nbsp;<a href="../../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Formulario Demanda</a>
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
                            <div id="datosPaciente">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <div align="center">
                                                Demanda
                                            <div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="1" width="100%">
                                                <tr>
                                                    <td>
                                                        Nombre y Apellido<br />
                                                        <div align="center">
                                                            <?=$demanda->getPaciente()->apellido." ".$demanda->getPaciente()->nombre; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Sexo<br />
                                                        <div align="center">
                                                            <?=$demanda->getPaciente()->sexo; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        Edad<br />
                                                        <div align="center">
                                                            <?=$demanda->getPaciente()->getEdadActual(); ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        NHC<br />
                                                        <div align="center">
                                                            <?=$demanda->getPaciente()->getNrodoc(); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan=4>
                                                        <br />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan=4>
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
                                                                        if($demanda->getEgreso()==null)
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
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan=4>
                                                        <?php
                                                        if($egreso->getId()!=NULL)
                                                        {
                                                        ?>
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
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                               
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
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
                            <li><a class="icon icon-pentool" href="#" id="agrEvolucionClinica">Evolucion Clinica</a></li>
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

        <form action="index.php" method="post" id="formDemanda">
            <input type="hidden" name="id" value="<?php echo $idAtencion; ?>">
        </form>
    </body>
</html>