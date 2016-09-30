<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$dbEpicrisis = new EpicrisisDatabaseLinker();

$id = $_REQUEST['id'];

$error = false;

$estados = $dbEpicrisis->estadosDeIngreso($id);
/*
if(count($estados)==0)
{
    $error = true;
    $message = "Ya se cargo el estado de ingreso";
}*/
if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>

    <div id="divPrincipal" title="Agregar estado" style="width: 150px; margin: 0 auto 0 auto">
        <form id="formDatos">
            Tipo Antecedente:<br>
            <select name="idEstadoIngreso">
                <?php
                    for ($i=0; $i < count($estados); $i++)
                    { 
                        echo "<option value=".$estados[$i]['id'].">".$estados[$i]['detalle']."</option>";
                    }
                ?>
            </select>
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
    
    <div id="divPrincipal" title="Agregar estado" style="width:300px; margin:0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>