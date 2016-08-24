<?php
/*Agregado para que tenga el usuario*/
include_once '../../../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$dbConsultorio = new ConsultorioDataBaseLinker();
$error = false;

if($_POST['subespecialidad']=="") {
    $error = true;
    $errorMesage = "Seleccione un consultorio para ver los dias <br/>";
}
else {
    $subespecialidad = $_POST['subespecialidad'];
    $profesional = $_POST['profesional'];
}

if(!$error) {
    $fechas = $dbConsultorio->getFechasConsultorio($subespecialidad, $profesional);
}
else {
    $error = true;
    $errorMesage = "No se encontraron fechas disponibles para este consultorio";
}

if(!$error) {
?>

<div class='fechasContenedor'>
    <table align="center" id="listado" class="tabla">
        <tr>
            <th>LUNES</th>
            <th>MARTES</th>
            <th>MIERCOLES</th>
            <th>JUEVES</th>
            <th>VIERNES</th>
            <th>SABADO</th>
            <th>DOMINGO</th>
        </tr>
        <?php
        for ($xf=0; $xf < count($fechas); $xf++) {
        ?>
        <tr>
            <td>
              <?php
                for ($i1=0; $i1 < count($fechas[$xf][1]); $i1++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][1][$i1]['fecha']."',".$fechas[$xf][1][$i1]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][1][$i1]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i2=0; $i2 < count($fechas[$xf][2]); $i2++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][2][$i2]['fecha']."',".$fechas[$xf][2][$i2]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][2][$i2]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i3=0; $i3 < count($fechas[$xf][3]); $i3++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][3][$i3]['fecha']."',".$fechas[$xf][3][$i3]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][3][$i3]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i4=0; $i4 < count($fechas[$xf][4]); $i4++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][4][$i4]['fecha']."',".$fechas[$xf][4][$i4]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][4][$i4]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i5=0; $i5 < count($fechas[$xf][5]); $i5++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][5][$i5]['fecha']."',".$fechas[$xf][5][$i5]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][5][$i5]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i6=0; $i6 < count($fechas[$xf][6]); $i6++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][6][$i6]['fecha']."',".$fechas[$xf][6][$i6]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][6][$i6]['fecha'])."</a></div>";
                }
              ?>
            </td>
            <td>
              <?php
                for ($i7=0; $i7 < count($fechas[$xf][7]); $i7++) {
                    echo "<div class='fechasConsultorios' ><a onclick=\"verHorarios('".$fechas[$xf][7][$i7]['fecha']."',".$fechas[$xf][7][$i7]['iddia'].");\"><span class='icon icon-notepad'></span>".Utils::sqlDateToHtmlDate($fechas[$xf][7][$i7]['fecha'])."</a></div>";
                }
              ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
<?php
}
?>