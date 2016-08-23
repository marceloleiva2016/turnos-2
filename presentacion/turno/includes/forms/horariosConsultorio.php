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
    <div class="horarioContenedor" >    
    <?php

    for ($i=0; $i < count($horarios); $i++) {
        $text = "<div class='horariosConsultorios' >";
        if($horarios[$i]['paciente']!=null) {
            $text .= "<span class='icon icon-confirm'></span><span>".$horarios[$i]['hora']." <span class='icon icon-next'></span> ".$horarios[$i]['paciente']."</span>";
        } else {
            $text .= "<span class='icon icon-plus'></span>".$horarios[$i]['hora']."<input class='horarioContenido' type='radio' id='horarioRadio' name='horarioRadio' value='".$horarios[$i]['hora']."' />";
        }
        echo $text."</div>";
    }
    echo "</div>";
}