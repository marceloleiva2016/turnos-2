<?php
include_once "../../../../namespacesAdress.php";
include_once datos."turnoDatabaseLinker.class.php";
include_once datos."pacienteDatabaseLinker.class.php";
include_once datos."obraSocialDatabaseLinker.class.php";

$idTurno = $_REQUEST['idturno'];

$dbPac = new PacienteDatabaseLinker();
$dbTurno = new TurnoDatabaseLinker();
$dbOsoc = new ObraSocialDatabaseLinker();

$varTurno = $dbTurno->obtenerVariablesTurno($idTurno);

$paciente = $dbPac->getDatosPacientePorNumero($varTurno['tipodoc'], $varTurno['nrodoc']);

$obraSocial = $dbOsoc->getObraSocialPaciente($varTurno['tipodoc'], $varTurno['nrodoc']);

$edad = $paciente->getEdadActual();
?>
<script type="text/javascript">

    $("#btnAceptar").click(function(event){
        event.preventDefault();
        confirmarTurno(idtur);
        $("#dialog").dialog("close");
    });

    $("#btnImprimir").click(function(event){
        event.preventDefault();
        confirmarTurno(idtur);
        $("#imprimir").jqprint();
        $("#dialog").dialog("close");
    });

</script>

<?php

if($obraSocial['id']==0)
{
    echo "<div id='divPrincipal' title='Confirmar Turno' style='width:500px; height:70px margin:0 auto 0 auto'>";
    echo "El paciente no tiene obra social registrada.<br>Presiona aceptar para confirmar el turno del paciente ".$paciente->getNombre()." ".$paciente->getApellido();
    echo "<div align='right'><input class='button-secondary' type='button' id='btnAceptar' value='ACEPTAR'/></div></div>";
}
else
{
?>
    <div id="divPrincipal" title="Imprimir Formulario" style="width:800px; height:99%; margin:0 auto 0 auto">
        <div id="imprimir">
            <table>
                <tr class="tbordS">
                    <td colspan="30"><font>Establecimiento</font></td>
                    <td colspan="6"><font>Codigo</font></td>
                </tr>
                <tr class="tbord">
                    <td class="tbord tW1 tH2" colspan="30">1-Hospital Municipal de Merlo "Eva Perón"</td>
                    <td class="tbord tW1 tH2 acd" colspan="6">26210</td>
                </tr>
                <tr>
                    <td class="tbordS" colspan="30"><font>Apellido y Nombre</font></td>
                    <td class="tbordS" colspan="6"><font>3-Fecha</font></td>
                </tr>
                <tr>
                    <td class="tbord tH2 acd" colspan="30"><?php echo $paciente->getNombre()." ".$paciente->getApellido(); ?></td>
                    <td class="tbord tW1 tH2">1</td>
                    <td class="tbord tW1 tH2">2</td>
                    <td class="tbord tW1 tH2">1</td>
                    <td class="tbord tW1 tH2">0</td>
                    <td class="tbord tW1 tH2">1</td>
                    <td class="tbord tW1 tH2">6</td>
                </tr>
                <tr>
                    <td class="tbordS tW2" ><font>4-Sexo</font></td>
                    <td class="tbordL tW2" ><font>5-Edad</font></td>
                    <td class="tbordL tW5" ><font>6-Nro de Documento</font></td>
                    <td class="tbordL tW5" colspan="14" ><font>7-Tipo de Documento</font></td>
                    <td class="tbordL" colspan="5"><font>8-C.E.</font></td>
                    <td class="tbordL" colspan="3"><font>9-SALA</font></td>
                    <td class="tbordL" colspan="3"><font>10-CAMA</font></td>
                    <td class="tbordL" colspan="8"><font>11-H.C.Nª</font></td>
                </tr>
                <tr>
                    <!--Sexo-->
                    <td class="tbord tH2 acd" ><?php echo $paciente->getSexo(); ?></td>
                    <!--Edad-->
                    <td class="tbord tW1 tH2 acd" ><?php echo $edad; ?></td>
                    <!--Nro de Documento-->
                    <td class="tbord tW1 tH2 acd" ><?php echo $paciente->getNroDoc(); ?></td>
                    <!--Tipo de Documento-->
                    <td class="tbord tW1 tH2 acd" colspan="14"><?php echo Utils::nombreCortoTipodoc($paciente->getTipoDoc()); ?></td>
                    <!--CE-->
                    <td class="tbord tW1 tH2" colspan="5" ></td>
                    <!--SALA-->
                    <td class="tbord tW1 tH2"  colspan="3"></td>
                    <!--CAMA-->
                    <td class="tbord tW1 tH2" colspan="3"></td>
                    <!--HCN-->
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                    <td class="tbord tW1 tH2" ></td>
                </tr>
                <tr>
                    <td colspan="2"><font>12-CONDICION</font></td>
                    <td class="tbordL tW5" colspan="2"><font>13-OBRA SOCIAL O MUTUAL</font></td>
                    <td class="tbordL" colspan="17"><font>14-TIPO DE AFILIADO</font></td>
                    <td class="tbordL" colspan="14"><font>15-Nª DE AFILIADO</font></td>
                </tr>
                <tr>
                    <td class="tbord" colspan="2"></td>
                    <td class="tbord" colspan="2"></td>
                    <td class="tbord tH2 tW2" colspan="8"></td>
                    <td class="tbord tH2 tW1" colspan="1"></td>
                    <td class="tbord tH2 tW2" colspan="3"></td>
                    <td class="tbord tH2 tW2" colspan="3"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                    <td class="tbord tW1"></td>
                </tr>
                <tr>
                    <td class="tbordL" colspan="32"><font>16-DIAGNOSTICO CLINICO</font></td>
                    <td class="tbordL" colspan="4"><font>17-CODIGO</font></td>
                </tr>
                <tr>
                    <td class="tbord tW1 tH2" colspan="32"></td>
                    <td class="tbord tW1 tH2"></td>
                    <td class="tbord tW1 tH2"></td>
                    <td class="tbord tW1 tH2"></td>
                    <td class="tbord tW1 tH2"></td>
                </tr>
                <tr>
                    <td class="tbordL tW8" colspan="2"><font>18-CANT. O COD.</font></td>
                    <td class="tbordL" colspan="20"><font>19-CONCEPTO</font></td>
                    <td class="tbordL" colspan="7"><font>20-UNITARIO</font></td>
                    <td class="tbordL" colspan="7"><font>21-TOTAL</font></td>
                </tr>
                <tr>
                    <td class="tbord tW1 tH8" colspan="2"></td>
                    <td class="tbord tW1 tH8" colspan="20"></td>
                    <td class="tbord tW1 tH8" colspan="7"></td>
                    <td class="tbord tW1 tH8" colspan="7"></td>
                </tr>
                <tr>
                    <td class="tbord" colspan="2"><font>22-ACLARACION</font></td>
                    <td class="tbord acd" colspan="10" rowspan="5"><font><br>-----------------------------------------<br>23-FIRMA PROFESIONAL<br><br>-----------------------------------------<br>SELLO Y MATRICULA</font></td>
                    <td class="tbordL al" colspan="15" rowspan="2"><font>24-TOTAL</font></td>
                    <td class="tbord" colspan="10" rowspan="2"></td>
                </tr>
                <tr>
                    <td class="tbord tH2" colspan="2" rowspan="3"></td>
                </tr>
                <tr>
                    <td class="tbord tH2 acd" colspan="15" rowspan="3"><font>25-CONFORME AFILIADO</font></td>
                </tr>
                <tr>
                    <td class="tbord tH2 alt" colspan="10" ><font>26-ABONAR AFILIADO</font></td>
                </tr>
                <tr>
                    <td class="tbord tH2" colspan="2"></td>
                    <td class="tbord tH2 alt" colspan="10"><font>ABOTARN POR O.S.</font></td>
                </tr>
                <tr>
                    <td class="tH1" colspan="8"><font>27-AUTORIZACION O.S.</font></td>
                    <td colspan="9"><font>INTERNACION/PRACTICA</font></td>
                    <td class="tbord alt" colspan="20" rowspan="2"><font>PRACTICA REALIZADA POR:</font><br><br><div class="acd"><font>-----------------------------------------<br>FIRMA PROFESIONAL<br><br>-----------------------------------------<br>SELLO Y MATRICULA</font></div></td>
                </tr>
                <tr>
                    <td class="tbord acd tH8" colspan="8"><font>-----------------------------------------<br>FIRMA<br><br>-----------------------------------------<br>ACLARACION</font></td>
                    <td class="tbord acd" colspan="9"><font>SELLO DELEGACION</font></td>
                    
                </tr>
            </table>
        </div>
    </div>

<?php

echo "<div id='divBtnImprimir' align='right'><input class='button-secondary' type='button' id='btnImprimir' value='IMPRIMIR'/></div>";

}
?>
<script type="text/javascript">

    $("#dialog" ).dialog({
        modal: true,
        width: $("#divPrincipal").width()+50,
        title:$("#divPrincipal").attr('title')
    });

</script>