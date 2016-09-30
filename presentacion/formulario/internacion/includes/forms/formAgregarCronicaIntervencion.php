<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$dbEpicrisis = new EpicrisisDatabaseLinker();

$id = $_REQUEST['id'];

$error = false;

$cronicaIntervenciones = $dbEpicrisis->obtenerCronicaIntervenciones($id);

if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>

    <div id="divPrincipal" title="INGRESAR INTERVENCIONES" style="width:200px; height:100px; margin:0 auto 0 auto">
        <form id="formDatos">
            <?php
            for ($i=0; $i < count($cronicaIntervenciones); $i++)
            {
                echo $cronicaIntervenciones[$i]->toStringHTML()."</br>";
            }
            ?>
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
    
    <div id="divPrincipal" title="INGRESAR INTERVENCIONES" style="width:300px; margin:0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>