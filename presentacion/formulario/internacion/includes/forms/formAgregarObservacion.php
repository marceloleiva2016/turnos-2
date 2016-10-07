<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Session Expirada. Por favor Actualice la pagina!";
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$error = false;

$message = "";

$dbDemanda = new DemandaDatabaseLinker();

$TipoObservacion = $dbDemanda->nombreTipoObservacion($_REQUEST['tipo_observacion']);

$observaciones = $dbDemanda->obtenerCantidadObservacionesDeTipo($_REQUEST['id'], $_REQUEST['tipo_observacion']);

if($observaciones>=5)
{
    $error = true;
    $message = "Puede ingresar hasta 5 items como maximo";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Agregar</title>
    <script type="text/javascript" src="../../includes/plug-in/jnumeric/jquery.numeric.js"></script>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php
if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>
    <form id="formDatos">
        <div id="divPrincipal" title="AGREGAR <?=$TipoObservacion;?>" style="width: 300px; margin: 0 auto 0 auto">
            <div id="divObservaciones" style="clear: both;">
            <?= $TipoObservacion;?>
            <textarea rows="10" cols="35" name="observacion" id="observacion"></textarea>
            <br />
                <a id ="cantidadLetras"> 0 de 240 caracteres</a>
            </div>
        </div>
    </form>
<?php
}
else
{
?>
    <script>
        frmOk = false;
    </script>
    <div id="divPrincipal" title="AGREGAR <?=$TipoObservacion;?>" style="width: 300px; margin: 0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message; ?>
        </div>
    </div>
<?php
} 
?>
<script>
$(document).ready(function() {
    $(".positive-integer").numeric({ decimal: false, negative: false }, function(){ 
        alert("Positive integers only"); this.value = ""; 
        this.focus(); 
    });
    $(".numeric").numeric();

    $("#observacion").bind('input propertychange', function(){
        if(($("#observacion").val().length) > 240)
        {
            $("#observacion").val($("#observacion").val().substring(0,240));
        }
        
        $("#cantidadLetras").html($("#observacion").val().length + " de 240 caracteres");
    });
});
</script>
</body>
</html>