<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once datos.'sectorDatabaseLinker.class.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbSector = new SectorDatabaseLinker();
$dbInt = new InternacionDatabaseLinker();
$dbGen = new GeneralesDatabaseLinker();

$idsector = $_REQUEST['idsector'];

$internados = $dbInt->getInternadosEnSector($idsector);

$sector = $dbSector->getSector($idsector);

?>
<section class="grid">
    <header class="top-bar">
        <img class="meta_logo" src="includes/img/authors/bed_logo_blue.png" />&nbsp;
        <h2 class="top-bar__headline">Internados en <?php echo $sector->getDetalle();?></h2>
        <div class="filter">
            <span><?php echo $sector->getEspecialidad();?></span>
        </div>
    </header>
    <?php
    $camas_libres = 0;
    $camas_ocupadas = 0;
    for ($i=0; $i < count($internados); $i++) {
        $cama = $internados[$i]['cama'];
        $internacion = $internados[$i]['internado'];
    ?>

    <a class="grid__item" href="#">
        <h2 class="title title--preview"><?php echo $cama->getNro()?></h2>
        <div class="loader"></div>
        <span class="category">
        <?php
        if($internacion!=null)
        {
            echo $internacion->getPaciente()->getNombre()." ".$internacion->getPaciente()->getApellido()."<br>";
            echo "<span>".Utils::nombreCortoTipodoc($internacion->getPaciente()->getTipodoc())."</span>-";
            echo "<span>".$internacion->getPaciente()->getNrodoc()."</span>";
            $camas_ocupadas++;
        }
        else
        {
            $camas_libres++;
            echo "Sin Internado";
        }
        ?>
        </span>
        <br>
    </a>

    <?php
    }
    ?>
    <footer class="page-meta">
        <span>Camas ocupadas: <?php echo $camas_ocupadas;?></span>&nbsp;
        <span>Camas libres: <?php echo $camas_libres;?></span>
    </footer>
</section>
<section class="content">
    <div class="scroll-wrap">
    <?php
        for ($i=0; $i < count($internados); $i++)
        {
            $cama = $internados[$i]['cama'];
            $internacion = $internados[$i]['internado'];

            echo "<article class='content__item'>";

            if($internacion!=null)
            {

                $pais = $dbGen->getPais($internacion->getPaciente()->getPais());
                $provincia = $dbGen->getProvincia($internacion->getPaciente()->getPais(), $internacion->getPaciente()->getProvincia());
                $partido = $dbGen->getPartido($internacion->getPaciente()->getPais(), $internacion->getPaciente()->getProvincia(), $internacion->getPaciente()->getPartido());
                $localidad = $dbGen->getLocalidad($internacion->getPaciente()->getPais(), $internacion->getPaciente()->getProvincia(), $internacion->getPaciente()->getPartido(), $internacion->getPaciente()->getLocalidad());
                if($internacion->getPaciente()->getDonante()==1){
                    $donante = "SI";
                } else if($internacion->getPaciente()->getDonante()==0){
                    $donante = "NO";
                } else {
                    $donante = "NO SABE";
                }
                $diagnostico = $internacion->getDiagnostico();
                ?>
                <div class="contenedorCabecera">
                    <div class="izq">
                        <img class="meta__avatar" src="includes/img/authors/1.png" alt="author01" />
                        <span class="category category--full">Nro <?php echo $cama->getNro()?></span>
                        <span class='meta__author'><?php echo $internacion->getPaciente()->getNombre()." ".$internacion->getPaciente()->getApellido(); ?></span><br>
                        <span class='meta__author'><?php  echo Utils::nombreCortoTipodoc($internacion->getPaciente()->getTipodoc())."-".$internacion->getPaciente()->getNrodoc(); ?></span>
                    </div>     
                    <?php
                    echo "<div class='datosPersona'><span class='meta__misc'>Datos Domicilio: ".$pais['descripcion'].", ".$provincia['descripcion'].", ".$partido['descripcion'].", ".$localidad['descripcion']."</span>";
                    echo "<span class='meta__misc'>Direccion: ".$internacion->getPaciente()->getCalleNombre()." ".$internacion->getPaciente()->getCalleNumero()."</span>";
                    echo "<span class='meta__misc'>Sexo: ".$internacion->getPaciente()->getSexoLargo()."</span>";
                    echo "<span class='meta__misc'>Edad: ".$internacion->getPaciente()->getEdadActual()." Años</span>";
                    echo "<span class='meta__misc'>Telefonos: ".$internacion->getPaciente()->getTelefono()."   ".$internacion->getPaciente()->getTelefono2()."</span>";
                    echo "</div>";
                    ?>
                </div>    
                <div class='meta meta--full'>
                <?php
                echo "<span class='meta__misc'>Fecha Ingreso: <i class='fa fa-calendar-o'></i>".Utils::sqlDateTimeToHtmlDateTime($internacion->getFecha_creacion())."</span>";
                echo "<span class='meta__misc'> Motivo Ingreso: ".$internacion->getMotivo_ingreso()."</span>";
                echo "<span class='meta__misc'>Es Donante?: ".$donante."</span>";
                echo "<span class='meta__misc'>Obra Social: ".$internacion->getObraSocial()['detalle']."</span>";
                echo "<span class='meta__misc'>Diagnostico Ingreso: ".$diagnostico['codigo_completo']."->".$diagnostico['descripcion']."</span>";
                echo "<div class='botones'><input type='button' class='button-secondary' value='EVOLUCION' onclick=javascript:mostrarFormulario('".$internacion->getId()."'); /></div></div>";
            }
            else
            {
                echo "<div class='meta meta--full'><br><br><span class='meta__author'>Sin Internado</span></div>";
            }
            ?>
               
            </article>
        <?php
        }
    ?>
    </div>
    <button class="close-button"><i class="fa fa-close"></i><span>Close</span></button>
</section>
<script src="includes/js/classie.js"></script>
<script src="includes/js/main.js"></script>