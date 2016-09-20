<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';

$dbTurnero = new TurneroDatabaseLinker();

$turnero = $dbTurnero->getTurnero(1);

$listado = $dbTurnero->getLlamadosCaducosTurnosEnConsultorios($turnero->getConsultorios());
?>
<table  align="center" class="letraGrande" >
<?php
for ($i=0; $i < count($listado); $i++) { 
?>
<tr>
    <td id="nombre" class="letraMedia">
        <?php 
        if($listado[$i]['estado_turno']=='3'){
            echo "<a  id='hora3' >"; 
        } else {
            echo "<a  id='hora2' >";
        }
        ?>
            <?=substr($listado[$i]['hora_llamado'], 0, -3)?>
        </a>&nbsp;
        <?php 
        if($listado[$i]['estado_turno']=='3'){
            echo "<strike>".$listado[$i]['paciente']."</strike><small><i>(Atendido)</i></small>"; 
        } else {
            echo $listado[$i]['paciente']; 
        }
        ?>
    </td>
    <td>
    </td>
    <td id="subEspecialidad" class="letraChica">
        <?=$listado[$i]['especialidad']?>
    </td>
    <td id="profesional" class="letraChica">
        <?=$listado[$i]['profesional']?>
    </td>
</tr>
<?php
}
?>
</table>