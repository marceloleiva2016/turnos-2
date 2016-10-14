<?php
include_once '../../../../../namespacesAdress.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once neg_formulario . 'formInternacion/formInternacionLaboratorio.class.php';

$idFormInt = Utils::postIntToPHP($_POST['id']);

$base = new FormInternacionDatabaseLinker();
$error = false;

//PREREQUISITOS
if(!$base->hayLaboratoriosSinHacer($idFormInt)) //tiene que tener algun laboratorio sin hacer
{
    $error = true;
    $message = "No hay laboratorios para hacer";
}

?>
<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Agregar resultados laboratorio Sangre</title>
    <script type="text/javascript" src="../../includes/plug-in/jnumeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="../../includes/plug-in/jqPrint/jquery.jqprint-0.3.js"></script>
</head>

<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php 
if(!$error)
{ 
?>
    <form action="ajaxAgregarLaboratorios.php" id="formDatos">
    <div id="divPrincipal" title="Agregar Laboratorios" style="width: 600px; margin: 0 auto 0 auto">
        
        
        <div id="divLaboratoriosSangre" style="clear: both;">
        
        <?php
            $tipo = TipoLaboratorio::SANGRE;
            $laboratorios = $base->laboratoriosSinHacer($idFormInt, $tipo); 
            
            $elementosPorFila = 8;
            $elementosDibujados = 0;
            $i=0;
        ?>
            
            <table cellspacing="8px">
            <thead>
            <tr>
                <th colspan="<?php echo $elementosPorFila; ?>" style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Sangre</th>
            </tr>
            </thead>
                <?php
                    /* @var $labo Laboratorio */
                    foreach ($laboratorios as $labo) {  
                        if($i%$elementosPorFila==0)
                        {
                            echo "<tr>";
                        }
                        //escribo datos 
                        
                        echo '<td title="' . utf8_encode(Utils::phpStringToHTML($labo->descripcion)) . '">';
                            echo "<b>". utf8_encode(Utils::phpStringToHTML($labo->nombre)) . "</b><br />";
                            if($labo->esNumerico)
                            {
                                echo '<input type="text" size="5" name="sangre['.$labo->id.']" class="numeric">';
                            }
                            else 
                            {
                                echo '<input type="text" size="5" name="sangre['.$labo->id.']" >';
                            }
                        echo "</td>";
                        
                        
                        if ($i%$elementosPorFila==7)
                        {
                            echo "</tr>";   
                        }
                        $i++;
                    }
                    //Si tengo que cerrar la ultima fila
                    if($i%$elementosPorFila!=0)
                    {
                        //echo '</tr>';
                        echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'"></td>';
                        echo '</tr>';
                    } 
                ?>
            
            </table>
        </div>
        
        <div id="divLaboratoriosOrina" style="clear: both;">
            
            <?php
            $tipo = TipoLaboratorio::ORINA;
            $laboratorios = $base->laboratoriosSinHacer($idFormInt, $tipo); 
            
            $elementosPorFila = 8;
            $elementosDibujados = 0;
            $i=0;
            ?>
            
            <table cellspacing="8px">
            <thead>
            <tr>
                <th colspan=" <?php echo $elementosPorFila; ?>" style="border-bottom: solid; border-bottom-width: 1pt; text-align: left;">Orina</th>
            </tr>
            </thead>
                <?php
                    /* @var $labo Laboratorio */
                    foreach ($laboratorios as $labo) {  
                        if($i%$elementosPorFila==0)
                        {
                            echo "<tr>";
                        }
                        //escribo datos 
                        
                        echo '<td title="' . $labo->descripcion . '">';
                            echo "<b>". $labo->nombre . "</b><br />";
                            if($labo->esNumerico)
                            {
                                echo '<input type="text" size="5" name="orina['.$labo->id.']" class="numeric">';
                            }
                            else
                            {
                                echo '<input type="text" size="5" name="orina['.$labo->id.']" >';
                            }
                        echo "</td>";
                        
                        
                        if ($i%$elementosPorFila==7)
                        {
                            echo "</tr>";   
                        }
                        $i++;
                    }

                    //Si tengo que cerrar la ultima fila
                    if($i%$elementosPorFila!=0)
                    {
                        //echo '</tr>';
                        
                        echo '<td colspan = "'.($elementosPorFila - (($i%$elementosPorFila!=0))).'">&nbsp;</td>';
                        echo '</tr>';
                        
                    }
                ?>
            
            </table>
        </div>
        
    </div>
    </form>
    <script>
        $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
        $(".numeric").numeric();
        frmOk = true;
    </script>
<?php
}
else //Si hay errores
{
?>
    <script>
        frmOk = false;
    </script>
    
    <div id="divPrincipal" title="Agregar Laboratorios" style="width: 500px; margin: 0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
} 
?>

</body>
</html>