<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$dbEpicrisis = new EpicrisisDatabaseLinker();

$id = $_REQUEST['id'];

$error = false;

$antecedentes = $dbEpicrisis->obtenerAntecedentesNoCargados($id);

if(count($antecedentes)==0)
{
    $error = true;
    $message = "No hay antecedentes que se puedan agregar";
}
if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>

    <div id="divPrincipal" title="Agregar Antecedente" style="width:300px; height:200px; margin:0 auto 0 auto">
        <form id="formDatos" action="../ajaxFunctions/ajaxAgregarAntecedente.php">
            Tipo Antecedente:<br>
            <select name="ant">
                <?php
                    for ($i=0; $i < count($antecedentes); $i++)
                    { 
                        echo "<option value=".$antecedentes[$i]['id'].">".$antecedentes[$i]['detalle']."</option>";
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
    
    <div id="divPrincipal" title="Agregar Antecedente" style="width:300px; margin:0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>