<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';
include_once nspcUsuario . 'usuario.class.php';

session_start();

if(!isset($_SESSION['usuario']))
{
    header ("Location: /epicrisis/index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$usuarioUnset = unserialize($usuario);

$error = false;

$message = "";

$dbEpicrisis = new EpicrisisDatabaseLinker();

$TipoObservacion = $dbEpicrisis->nombreTipoObservacion($_REQUEST['tipo_observacion']);

$observaciones = $dbEpicrisis->obtenerCantidadObservacionesDeTipo($_REQUEST['id'], $_REQUEST['tipo_observacion']);

if($observaciones>=2)
{
    $error = true;
    $message = "Puede ingresar hasta dos items como maximo";
}

?>
<!DOCTYPE html PUBLIC>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Agregar</title>
    <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
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