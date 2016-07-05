<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';
include_once datos . 'utils.php';

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

$message = "";

$idObservacion = Utils::postIntToPHP($_REQUEST['id']);

$observacion = $dbDemanda->obtenerObservacion($idObservacion);

?>

<!DOCTYPE html PUBLIC>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Editar Observacion</title>
    <script type="text/javascript" src="../../includes/plug-in/jnumeric/jquery.numeric.js"></script>
    <!--<script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>-->
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
        <div id="divPrincipal" title="Editar" style="width: 300px; margin: 0 auto 0 auto">
            <div id="divObservaciones" style="clear: both;">
            <textarea rows="10" cols="35" name="observacion" id="observacion"><?php echo $observacion['detalle']; ?></textarea>
            <br/>
                <a id ="cantidadLetras"> 0 de 240 caracteres</a>
            </div>
            <br/>
            <input type="hidden" name="idObs" value="<?php echo $idObservacion; ?>"/>
    </form>
<?php
}
else
{
?>
    <script>
        frmOk = false;
    </script>
    
    <div id="divPrincipal" title="Error Editando" style="width: 500px; margin: 0 auto 0 auto">
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