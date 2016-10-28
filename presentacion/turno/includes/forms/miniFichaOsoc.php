<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
include_once datos.'utils.php';

$paciente = new PacienteDataBaseLinker();
$osocDB = new ObraSocialDatabaseLinker();

$error = false;

if($_POST['nroDoc']=="")
{
    $error = true;
    $errorMesage = "Error tratando de leer los datos del documento <br/>";
    die();
}
else
{
    $tipodoc = $_POST['tipoDoc'];
    $nrodoc = $_POST['nroDoc'];
}

if(!$error)
{
    $pac = $paciente->getDatosPacientePorNumero($tipodoc, $nrodoc);

    $url = 'https://sisa.msal.gov.ar/sisa/services/rest/puco/'.$nrodoc;

    $value = file_get_contents($url);

    $obraSocialRemoto = simplexml_load_string($value);

    if ($pac->getNombre()=="")
    {
        $error = true;
        $errorMesage = "No se encontro Obra Social";
    }
    else
    {
        $osoc = $osocDB->getObraSocialPaciente($tipodoc, $nrodoc);
    }
}
else
{
    $error = true;
    $errorMesage = "El n&uacute;mero y tipo de documento introducido, no corresponde a un paciente del sistema";
}

if(!$error)
{
?>
    <div class="contenedorObraSocial">

        <div class="pageOsoc" >
            <h3>Datos Obra Social Local</h3>
            <hr>
            <b><?php echo Utils::phpStringToHTML($osoc['obra_social'])?></b>
        </div>
        <div class="pageOsoc" >
            <h3>Datos Obra Social Remoto</h3>
            <hr>
            <?php
            if($obraSocialRemoto->resultado=="OK")
            {
                echo "<b>Os:".$obraSocialRemoto->coberturaSocial."</br>";
                echo $obraSocialRemoto->tipodoc."-".$obraSocialRemoto->nrodoc." / ".$obraSocialRemoto->denominacion."</b>";
            }
            else
            {
                echo "<b>NO ENCONTRADO</b>";
            }
            ?>
            
        </div>
    </div>
<?php
}
else
{
?>

<div class="contenedorObraSocial">
    <div class="imagenInterrogacion">
    </div>
    <div class="imagenMessage">
        <b><p align="center"> <?php echo $errorMesage?></p></b>
    </div>
</div>

<?php
}