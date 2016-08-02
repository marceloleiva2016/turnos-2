<?php
include_once '../../../namespacesAdress.php';
include_once datos.'atencionDatabaseLinker.class.php';

$tipodoc = $_POST['tipoDoc'];
$nrodoc = $_POST['nroDoc'];
$esp = $_POST['especialidad'];

if($esp!=null AND !isset($esp))
{
  echo "<div align='center'>
          <h3>Datos de Paciente Inexistentes</h3>
        </div>";
  die();
}
else
{
    $atencionDB = new AtencionDatabaseLinker();
    $atenciones = $atencionDB->getAtencionesEnPaciente($tipodoc, $nrodoc, $esp);
    if(count($atenciones)==0)
    {
        echo "<div align='center'>
                <h3>Sin atenciones para la especialidad seleccionada</h3>
             </div>";
        die();
    }
}

?>
<script type="text/javascript">
            
    function llenarFormularios(){
        <?php
        for ($i=0; $i < count($atenciones); $i++) {
            echo "$('#atencionNro".$atenciones[$i]['idatencion']."').load('../formulario".$atenciones[$i]['ubicacion']."formResumen.php?id=".$atenciones[$i]['idatencion']."');";
        }
        ?>
    }
</script>
<ul class="cbp_tmtimeline">
    <?php
    for ($i=0; $i < count($atenciones); $i++) {
    ?>
    <li>
        <time class="cbp_tmtime" datetime='<?php echo $atenciones[$i]['fecha_creacion']; ?>'>
            <span><?php echo Utils::sqlDateToHtmlDate($atenciones[$i]['fecha']); ?></span>
            <span><?php echo $atenciones[$i]['hora']; ?></span>
        </time>
        <div class='<?php echo "icon ".$atenciones[$i]['icono']; ?>'>
        </div>
        <div class="cbp_tmlabel">
            <h1><?php echo $atenciones[$i]['tipo_atencion']; ?></h1>
            <h2><?php echo $atenciones[$i]['especialidad'].' | '.$atenciones[$i]['subespecialidad'].' | '.$atenciones[$i]['profesional']; ?></h2>
            <p id='<?php echo "atencionNro".$atenciones[$i]['idatencion']; ?>' ></p>
        </div>
    </li>
    <?php
    }
    ?>
</ul>