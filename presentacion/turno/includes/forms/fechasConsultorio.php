<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbConsultorio = new ConsultorioDataBaseLinker();
$error = false;

if($_POST['subespecialidad']=="") {
    $error = true;
    $errorMesage = "Seleccione un consultorio para ver los dias <br/>";
}
else {
    $subespecialidad = $_POST['subespecialidad'];
    $profesional = $_POST['profesional'];
}

if(!$error) {
    $fechas = $dbConsultorio->getFechasConsultorio($subespecialidad, $profesional);
}
else {
    $error = true;
    $errorMesage = "No se encontraron fechas disponibles para este consultorio";
}

if(!$error) {
    echo "<div class='fechasContenedor'>";

    for ($i=0; $i < count($fechas); $i++) {
        ?>
        <div class='fechasConsultorios' >
            <a onclick="verHorarios('<?php echo $fechas[$i]['fecha']."',".$fechas[$i]['iddia']; ?> );"><span class='icon icon-notepad'> </span><?php echo $fechas[$i]['dia']." ".Utils::sqlDateToHtmlDate($fechas[$i]['fecha']); ?></a>
        </div>
        <?php
    }
    echo "</div>";
}