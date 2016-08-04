<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once datos.'utils.php';

$consulDb = new ConsultorioDatabaseLinker();

$idConsultorio = $_REQUEST['idconsultorio'];

$horarios = $consulDb->getHorarios($idConsultorio);

?>
<script type="text/javascript">
  function bajaHorario(id) {

    $("#dialog:ui-dialog").dialog("destroy");
        $("#dialog-message").css('visibility',"visible");
        $("#dialogBajaHorario" ).load("includes/forms/formDialogBorrarHorario.php",{idhorario:id});
        $("#dialogBajaHorario" ).dialog({
            modal: true,
            width: 300,
            title: "Eliminar Horario",
            buttons: {
                "Aceptar": function(){
                    $.ajax({
                        data: "id="+id,
                        type: "POST",
                        dataType: "json",
                        url: "includes/ajaxFunctions/borrarHorario.php",
                        success: function(data) {
                            alert(data.message);
                            if(data.result) {
                                $("#horarios").load("includes/forms/formTablaHorarios.php",{idconsultorio:<?php echo $idConsultorio; ?>});
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Cerrar":function() {
                    $(this).dialog("close");
                }
            }
        });
  }
</script>

<table align="center" border="1" id="listado" class="tabla">
  <tr>
    <th>LUNES</th>
    <th>MARTES</th>
    <th>MIERCOLES</th>
    <th>JUEVES</th>
    <th>VIERNES</th>
    <th>SABADO</th>
    <th>DOMINGO</th>
  </tr>
  <tr>
    <td>
      <?php
        for ($i1=0; $i1 < count($horarios[1]); $i1++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[1][$i1]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[1][$i1]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[1][$i1]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i2=0; $i2 < count($horarios[2]); $i2++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[2][$i2]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[2][$i2]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[2][$i2]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i3=0; $i3 < count($horarios[3]); $i3++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[3][$i3]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[3][$i3]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[3][$i3]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i4=0; $i4 < count($horarios[4]); $i4++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[4][$i4]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[4][$i4]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[4][$i4]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i5=0; $i5 < count($horarios[5]); $i5++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[5][$i5]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[5][$i5]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[5][$i5]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i6=0; $i6 < count($horarios[6]); $i6++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[6][$i6]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[6][$i6]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[6][$i6]['hora_hasta'])."<br>";
        }
      ?>
    </td>
    <td>
      <?php
        for ($i7=0; $i7 < count($horarios[7]); $i7++) {
          echo "<a class='iconoBlue' onclick='bajaHorario(".$horarios[7][$i7]['id'].");'><span class='icon icon-delete'> </span></a> ".Utils::sqlTimeToHtmlTime($horarios[7][$i7]['hora_desde'])." a ".Utils::sqlTimeToHtmlTime($horarios[7][$i7]['hora_hasta'])."<br>";
        }
      ?>
    </td>
  </tr>
</table>