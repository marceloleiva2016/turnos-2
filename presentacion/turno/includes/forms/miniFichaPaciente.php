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
    <div id="contenido" style="float:right; width:450px;height:210px;  border-color: black; border-width: 0pt; border-style: solid; background-color:#e9e9e9;">

        <div id= "datosPaciente" style="margin: 0px 0px 0px 10px;float:left; top: 10px; left: 10px;">
            <br />
            <b><?php echo htmlentities($nombrePaciente)?></b><br>
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
        <div id="picture" style="margin: 20px 20px 10px 10px; width: 100px; height: 100px;position:relative; top:10px; left:320px; border-color: black; border-width: 1pt; border-style: solid; background-image: url(includes/images/hombre.png);  background-color:#FFFFFF;"></div>
    <?php
    }
    else
    {
    ?>
        <div id="picture" style="margin: 20px 20px 10px 10px; width: 100px; height: 100px;position:relative; top:10px; left:320px;border-color: black; border-width: 1pt; border-style: solid; background-image: url(includes/images/mujer.png); background-color:#FFFFFF;"></div>
    <?php
    }
    echo "</div>";
}
else
{
?>

<div id="contenido" style="float:right; width:450px;height:210px;  border-color: black; border-width: 0pt; border-style: solid; background-color:#e9e9e9;">
    <div id="picture" style="margin: 20px 20px 10px 10px; width: 100px; height: 100px;position:relative; top:10px; left:320px; background-image: url(includes/images/question_white.png);">
    </div>
    <div id="message" style="border: 0pt; border-color: black; border-style: solid; width: 400px;position:relative; left: 10px">
        <b><p align="center"> <?php echo $errorMesage?></p></b>
    </div>
</div>

<?php
}