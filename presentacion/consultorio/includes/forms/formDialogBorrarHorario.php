<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$consulDb = new ConsultorioDatabaseLinker();

$idhorario = $_REQUEST['idhorario'];

$horario = $consulDb->getHorario($idhorario);

?>
<div>
    Ud. esta por eliminar el horario de los <?php echo $horario['nombre']; ?> de <?php echo Utils::sqlTimeToHtmlTime($horario['hora_desde']); ?> a <?php echo Utils::sqlTimeToHtmlTime($horario['hora_hasta']); ?> 
    <br>Esta seguro?

</div>