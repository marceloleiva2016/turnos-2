<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'utils.php';

$paciente = new PacienteDataBaseLinker();
$error = false;

if($_POST['nroDoc']=="")
{
    $error = true;
    $errorMesage = "Error tratando de leer los datos del documento <br/>";
}
else
{
    $tipodoc = $_POST['tipoDoc'];
    $nrodoc = $_POST['nroDoc'];
}

if(!$error)
{
    $pac = $paciente->getDatosPacientePorNumero($tipodoc, $nrodoc);

    if ($pac->getNombre()=="")
    {
        $error = true;
        $errorMesage = "El paciente no existe";
    }

    $nombrePaciente = $pac->getNombre().", ".$pac->getApellido();
    $edad = $pac->getEdadActual();
    $domicilio = $pac->getCalleNombre().", ".$pac->getCalleNumero();
    $tel = htmlentities($pac->getTelefono());
    $sexo = $pac->getSexo();
    $fechaNac = Utils::sqlDateToHtmlDate($pac->getFechaNacimiento());
    
}
else
{
    $error = true;
    $errorMesage = "El n&uacute;mero y tipo de documento introducido, no corresponde a un paciente del sistema";
}

?>

<?php
if(!$error)
{
?>
    <div class="contenedorPaciente">

        <div class="datosPaciente" >
            <br />
            <b><?php echo Utils::phpStringToHTML($nombrePaciente)?></b><br>
            <br /> 
            <b>Fecha Nacimiento: </b><?php echo $fechaNac?><br>
            <b>Edad: </b><?php echo $edad?><br>
            <b>Domicilio: </b><?php echo $domicilio?><br>
            <b>Telefono: </b><?php echo $tel?><br />
            <br />
        </div>

    <?php

    if($sexo == "M")
    { 
    ?>
        <div class="imagenSexoPacienteHombre"></div>
    <?php
    }
    else
    {
    ?>
        <div class="imagenSexoPacienteMujer"></div>
    <?php
    }
    echo "</div>";
}
else
{
?>

<div class="contenedorPaciente">
    <div class="imagenInterrogacion">
    </div>
    <div class="imagenMessage">
        <b><p align="center"> <?php echo $errorMesage?></p></b>
    </div>
</div>

<?php
}