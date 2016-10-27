<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'sectorDatabaseLinker.class.php';

$dbSect = new SectorDatabaseLinker();

$sectores = $dbSect->getSectores();

session_start();

if(!isset($_SESSION['usuario']))
{
    echo "Debe Presionar F5 por que su session expiro.";
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
?>
<!DOCTYPE html>
<html>
    <script>

        function validar()
        {
            if($('#nro_cama').val()=='') {
                //NOTIFICACION
                // create the notification
                var notification = new NotificationFx({
                    message : '<span class="icon icon-message"></span><p>Debe ingresar el numero de la cama</p>',
                    layout : 'attached',
                    effect : 'bouncyflip',
                    type : 'notice'
                });
                // show the notification
                notification.show();
                //NOTIFICACION
                return false;
            } else {
                return true;
            }
        }

    </script>
<body>
    <div id="divPrincipal" title="Agregar Cama" style="margin:0 auto 0 auto">
        <form method="post" name="formCama" id="formCama" >
            <label>Cama</label><br>
            <input type="number" name="nro_cama" id="nro_cama" placeholder="Numero" /><br/>
            <label>Sector</label><br>
            <select id="idsector" name="idsector">
                <?php
                for ($i=0; $i < count($sectores); $i++) {
                    echo "<option value=".$sectores[$i]->getId()." >".$sectores[$i]->getDetalle()."</option>";
                }
                ?>
            </select>
        </form>
    </div>
</body>
</html>