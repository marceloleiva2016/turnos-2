<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbConsultorio = new ConsultorioDataBaseLinker();
$error = false;

if($_POST['subespecialidad']=="")
{
    $error = true;
    $errorMesage = "Seleccione un consultorio para ver los dias <br/>";
}
else
{
    $subespecialidad = $_POST['subespecialidad'];
    $profesional = $_POST['profesional'];
}

if(!$error)
{

    $fechas = $dbConsultorio->getFechasConsultorio($subespecialidad, $profesional);

}
else
{
    $error = true;
    $errorMesage = "No se encontraron fechas disponibles para este consultorio";
}

if(!$error)
{
    echo "<div>";

    for ($i=0; $i < count($fechas); $i++) {
        ?>
        <a onclick="verHorarios('<?php echo $fechas[$i]['fecha']."',".$fechas[$i]['iddia']; ?> );"><span class='icon icon-next'> </span><?php echo $fechas[$i]['dia']." ".Utils::sqlDateTimeToHtmlDateTime($fechas[$i]['fecha']); ?></a><br>
        <?php
    }

    echo "</div>";
}