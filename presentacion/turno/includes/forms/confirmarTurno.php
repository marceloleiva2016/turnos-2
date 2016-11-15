<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
include_once datos.'utils.php';

$paciente = new PacienteDataBaseLinker();
$turnoDB = new TurnoDatabaseLinker();
$osocDB = new ObraSocialDatabaseLinker();

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
    $domicilio = Utils::phpStringToHTML($pac->getCalleNombre().", ".$pac->getCalleNumero());
    $tel = Utils::phpStringToHTML($pac->getTelefono());
    $sexo = $pac->getSexo();
    $fechaNac = Utils::sqlDateToHtmlDate($pac->getFechaNacimiento());

    if(count($turnos)==0)
    {
        $error = true;
        $errorMesage = "El paciente ".Utils::phpStringToHTML($nombrePaciente)." <br>no tiene turnos asignados en el sistema";
    }

    if ($pac->getNombre()=="")
    {
        $error = true;
        $errorMesage = "El paciente no existe";
    }
    else
    {
        $osoc = $osocDB->getObraSocialPaciente($tipodoc, $nrodoc);
    }
    
}
else
{
    $error = true;
    $errorMesage = "El n&uacute;mero y tipo de documento introducido,<br> no corresponde a un paciente del sistema";
}

?>

<?php
if(!$error)
{
?>
    <div class="contenedorPaciente">
        <div class="subContenedorPaciente">
            <div class="datosPaciente" >
                <br/>
                <b><?php echo Utils::phpStringToHTML($nombrePaciente)?></b><br>
                <br /> 
                <b>Fecha Nacimiento: </b><?php echo $fechaNac?><br>
                <b>Edad: </b><?php echo $edad?><br>
                <b>Domicilio: </b><?php echo $domicilio?><br>
                <b>Telefono: </b><?php echo $tel?><br />
                <br/>
            </div>
    <?php

        if($sexo == "M")
        { 
        ?>
            <div class="imagenSexoPacienteHombre"></div><br>
        <?php
        }
        else
        {
        ?>
            <div class="imagenSexoPacienteMujer"></div><br>
        <?php
        }
        ?>
        </div>
        <div -style="display: inline-block;">
            <h3>Datos Obra Social Local</h3>
            <hr>
            <b><?php echo Utils::phpStringToHTML($osoc['obra_social'])?></b>
        </div>
    </div>
    <div id="listaDeTurnos">
        <?php 
            for ($i=0; $i < count($turnos); $i++) { 
                echo "<input type='radio' id='turnoRadio' name='turnoRadio' value='".$turnos[$i]['id']."' >".$turnos[$i]['especialidad']." | ".$turnos[$i]['subespecialidad']." Prof:".$turnos[$i]['profesional']." | ".$turnos[$i]['fecha']." ".$turnos[$i]['hora']."<br>";
            }
        ?>
    </div>
<?php
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