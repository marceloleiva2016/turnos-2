<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'utils.php';

$paciente = new PacienteDataBaseLinker();
$turnoDB = new TurnoDatabaseLinker();

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
    $turnos = $turnoDB->getTurnoAsignadoDePaciente($tipodoc, $nrodoc);
    $pac = $paciente->getDatosPacientePorNumero($tipodoc, $nrodoc);

    $nombrePaciente = $pac->getNombre().", ".$pac->getApellido();
    $edad = $pac->getEdadActual();
    $domicilio = $pac->getCalleNombre().", ".$pac->getCalleNumero();
    $tel = htmlentities($pac->getTelefono());
    $sexo = $pac->getSexo();
    $fechaNac = Utils::sqlDateToHtmlDate($pac->getFechaNacimiento());

    if(count($turnos)==0)
    {
        $error = true;
        $errorMesage = "El paciente ".$nombrePaciente." no tiene turnos asignados en el sistema";
    }

    if ($pac->getNombre()=="")
    {
        $error = true;
        $errorMesage = "El paciente no existe";
    }
    
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
    <div id="contenido" style="float:right; width:auto;height:auto;  border-color: black; border-width: 0pt; border-style: solid; background-color:#e9e9e9;">

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
    	<!-- imagen de Hombre -->
        <div id="picture" style="margin: 10px 10px 10px 10px; width: 100px; height: 100px;position:relative; top:10px; left:200px; border-color: black; border-width: 1pt; border-style: solid; background-image: url(includes/images/hombre.png);  background-color:#FFFFFF;"></div>
    <?php
    }
    else
    {
    ?>
    	<!-- imagen de Mujer -->
        <div id="picture" style="margin: 10px 10px 10px 10px; width: 100px; height: 100px;position:relative; top:10px; left:200px;border-color: black; border-width: 1pt; border-style: solid; background-image: url(includes/images/mujer.png); background-color:#FFFFFF;"></div>
    <?php
    }
    ?>
	    <div id="listaDeTurnos">
		    <br><br><br><br>
		    
		    <?php 
		    	for ($i=0; $i < count($turnos); $i++) { 
		            echo "<input type='radio' id='turnoRadio' name='turnoRadio' value='".$turnos[$i]['id']."' >".$turnos[$i]['especialidad']." | ".$turnos[$i]['subespecialidad']." Prof:".$turnos[$i]['profesional']." | ".$turnos[$i]['fecha']." ".$turnos[$i]['hora']."<br>";
		        }
		    ?>
	    </div>
    </div>
<?php
}
else
{
?>

	<div id="contenido" style="float:right; width:450px;height:210px;  border-color: black; border-width: 0pt; border-style: solid; background-color:#e9e9e9;">
	    <div id="picture" style="margin: 20px 20px 10px 10px; width: 100px; height: 100px; background-image: url(includes/images/question_white.png);">
	    </div>
	    <div id="message" style="border: 0pt; border-color: black; border-style: solid; width: 400px;position:relative; left: 10px">
	        <b><p align="center"> <?php echo $errorMesage?></p></b>
	    </div>
	</div>

<?php
}