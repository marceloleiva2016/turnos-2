<?php
include_once '../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';

include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'utils.php';
include_once neg_formulario.'demanda/demanda.class.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/
$idAtencion = $_REQUEST['id'];

$dbDemanda = new DemandaDatabaseLinker();

$demanda = $dbDemanda->obtenerFormulario($idAtencion,$data->getId());

$observaciones = $demanda->getObservaciones();

$egreso = $demanda->getEgreso();

for ($q=0; $q < count($observaciones); $q++)
{
?>
    <h2><?=$observaciones[$q]->getDetalle();?></h2>
    <div class="lstTextos">
        <?php
        $items = $observaciones[$q]->getitemsObservacion();

        for ($y=0; $y < count($items); $y++)
        {
            $title = "Fecha:" .$items[$y]->getFecha()." | Usuario:" .$items[$y]->getUsuario();
            echo "<li title='".$title."'>";
            echo "*<span class=\" context-menu-one box menu-1\" obsId=\"".$items[$y]->getId()."\" obsUsr=\"".$items[$y]->getUsuario()."\">\n";
                
            print $items[$y]->getDetalle();
                
            echo "</span>";
            echo "</li>";
        }
        ?>
        <br>
    </div>
</div>
<?php
}

if($egreso->getId()!=NULL)
{
?>
    <h2>Egreso</h2>
    <div align="left">
        <b>DIAGNOSTICO:</b>
        <?=$egreso->getDiagnostico();?><br>
        <b>DESTINO:</b>
        <?=$egreso->getTipoEgreso();?><br>
        <b>FECHA:</b>
        <?=Utils::sqlDateToHtmlDate($egreso->getFechaCreacion());?><br>
        <b>PROFESIONAL:</b>
        <?=$egreso->getUsuario();?>
    </div>
<?php
}