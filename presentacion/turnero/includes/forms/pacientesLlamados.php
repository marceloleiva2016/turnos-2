<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'turneroDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbTurnero = new TurneroDatabaseLinker();

$turnero = $dbTurnero->getTurnero(1);

$listado = $dbTurnero->getLlamadosTurnosEnConsultorios($turnero->getConsultorios());

for ($i=0; $i < count($listado); $i++) { 
    if($i==0){
        $clase = "cajaListado";
    } else {
        $clase = "cajaListadoVisible";
    }   
    
?>
<div id="caja" class="<?=$clase?>" >
    <table  align="center">
        <tr>
            <td rowspan='3' id='hora' class='letraMuyGrande' ><?= substr($listado[$i]['hora_llamado'], 0, -3)?></td>
            <td rowspan='3' >&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td class="letraMuyGrande" colspan="2">
                <div id="nombrePaciente" >
                    <?=$listado[$i]['paciente']?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="letraConsult">
                <?=$listado[$i]['especialidad']?>
            </td>
            <td class="letraConsult" align="right">
                <?=$listado[$i]['profesional']?>
            </td>
        </tr>
    </table>
</div>
<?php
}

if(count($listado)==0) {
    echo "<div class='letraMuyGrande esperando' align='center' ><img id='loading' src='includes/img/ripple.gif' alt='Cargando...'/>Esperando llamados...</div>";
}
?>