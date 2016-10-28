<?php

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
    $url = 'https://sisa.msal.gov.ar/sisa/services/rest/puco/'.$nrodoc;

    $value = file_get_contents($url);

    $obraSocialRemoto = simplexml_load_string($value);
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
        <h3>Datos Externos</h3>
        <hr>
        <?php
        if($obraSocialRemoto->resultado=="OK")
        {
            echo "<b>".$obraSocialRemoto->tipodoc."-".$obraSocialRemoto->nrodoc." </br> ".$obraSocialRemoto->denominacion."</br><h3>Obra Social</h3><hr>";
            echo $obraSocialRemoto->coberturaSocial."</b>";
        }
        else
        {
            echo "<b>NO REGISTRADO</b>";
        }
        ?>
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