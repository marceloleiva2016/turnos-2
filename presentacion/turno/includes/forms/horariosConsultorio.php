<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbConsultorio = new ConsultorioDataBaseLinker();
$error = false;

if($_POST['fecha']=="")
{
    $error = true;
    $errorMesage = "Seleccione una fecha para ver horarios disponibles <br/>";
}
else
{
    $fecha = $_POST['fecha'];
    $iddia = $_POST['iddia'];
    $subespecialidad = $_POST['subespecialidad'];
    $profesional = $_POST['profesional'];
}

if(!$error)
{
    $horarios = $dbConsultorio->getHorariosEnFechaConsultorio($subespecialidad, $profesional, $fecha, $iddia);
}
else
{
    $error = true;
    $errorMesage = "No se encontraron horarios para la fecha seleccionada";
}

if(!$error)
{
    ?>
    <input type='hidden' id='fechaSeleccionada' value='<?php echo $fecha; ?>'>
    <br>
    <?php

    for ($i=0; $i < count($horarios); $i++) { 
        echo "<input type='radio' id='horarioRadio' name='horarioRadio' value='".$horarios[$i]['hora']."' >".$horarios[$i]['hora']."<br>";
    }
}