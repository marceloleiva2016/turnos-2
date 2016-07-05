<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$dbEpicrisis = new EpicrisisDatabaseLinker();

$id = $_REQUEST['id'];

$error = false;

$examenes = $dbEpicrisis->obtenerTiposExamenesNoCargados($id);

if(count($examenes)==0)
{
    $error = true;
    $message = "Ya no hay examenes para agregar";
}
if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>

    <div id="divPrincipal" title="Agregar examen complementario" style="width:300px; height:200px; margin:0 auto 0 auto">
        <form id="formDatos" action="../ajaxFunctions/ajaxAgregarExamenComplementario.php">
            Tipo de examen:<br>
            <select name="ant">
                <?php
                    for ($i=0; $i < count($examenes); $i++)
                    { 
                        echo "<option value=".$examenes[$i]['id'].">".$examenes[$i]['detalle']."</option>";
                    }
                ?>
            </select><br>
            Detalle:<br>
            <textarea name="detalleAnt" rows="7" cols="34"></textarea>
        </form>
    </div>
<?php
}
else //si hay errores
{
?>
    <script>
        frmOk = false;
    </script>
    
    <div id="divPrincipal" title="Agregar examen complementario" style="width:300px; margin:0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>