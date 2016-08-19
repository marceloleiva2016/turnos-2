<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';
session_start();

/*Agregado para que tenga el usuario*/
if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);
/*fin de agregado usuario*/

$idAnterior = $_REQUEST['id'];

if($idAnterior==""){
  $idAnterior = "sinobra";
}

$tipodoc = $_REQUEST['tipodoc'];
$nrodoc = $_REQUEST['nrodoc'];

$obsocDB = new ObraSocialDatabaseLinker();

?>
<script type="text/javascript">

$(document).ready(function() {

  $('#guardarOsoc').click(function(event){
    event.preventDefault();
    $.ajax({
      data: $( "#formObraSocial" ).serialize()+"&nrodoc="+<?php echo $nrodoc; ?>+"&tipodoc="+<?php echo $tipodoc; ?>+"&idAnterior=<?php echo $idAnterior; ?>",
      type: "POST",
      dataType: "json",
      url: "includes/ajaxFunctions/ajaxModificarObraSocial.php",
        success: function(data)
        {
          alert(data.message);
          if(data.ret)
          {
            $('#somedialog-close').click();
            $("#apartadoObraSocial").load("includes/forms/obraSocialActual.php",{tipodoc:<?php echo $tipodoc; ?>, nrodoc:<?php echo $nrodoc; ?>});
          }
        }
    });
  });

  $('#osocFiltrar').click(function(event){
    event.preventDefault();
    var det = $('#det_busq').val();
    vaciarComboObrasSociales();
    $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/ajaxFunctions/jsonObrasSociales.php',
        data:{Det:det},
        success: function(json)
        {
            obrasSociales = json;
            cargarOptions($('#osoc'),obrasSociales);
        }
    });
  });
});
</script>
<div class="Obra Social">
  <!--Titulo-->
  <h2>
    <strong>Modificar Obra Social</strong>
  </h2>
  <!-- /Titulo-->
  <!--Cuerpo-->
  <h3>Filtrar</h3>
  <hr>
  <form id="formObraSocial">
    <input name="det_busq" id="det_busq" placeholder="Detalle">
    <input class="button-secondary" name="osocFiltrar" id="osocFiltrar" type="submit" value="Buscar"><br>
    <select id="osoc" name="osoc">
      <?php
        $obrasSociales = $obsocDB->getObrasSocialesActivas();
          for ($i=0; $i < count($obrasSociales); $i++) {
            echo "<option value=".$obrasSociales[$i]['id'].">".substr($obrasSociales[$i]['detalle'],0,80)."</option>";
          }
      ?>
    </select>
    <!--Cuerpo-->
    <h3>Datos de Facturacion</h3>
    <hr>
      <label>Nro de Afiliado :</label><input type="text" name="osoc_nro_afiliado" id="osoc_nro_afiliado" /><br>
      <label>Empresa :</label><input type="text" name="osoc_empresa" id="osoc_empresa" /><br>
      <label>Direccion :</label><input type="text" name="osoc_direccion" id="osoc_direccion" /><br>
      <label>Fecha de emision :</label><input type="text" name="osoc_fecha_emision" id="osoc_fecha_emision" placeholder="dd/mm/aaaa"/><br>
      <label>Fecha de vencimiento :</label><input type="text" name="osoc_fecha_vencimiento" id="osoc_fecha_vencimiento" placeholder="dd/mm/aaaa"/><br>
      <input type="hidden" name="idusuario" id="idusuario" value='<?=$data->getId()?>'><br>
      <input id="guardarOsoc" class="button-secondary" type="submit" value="Guardar">
    </form>
</div>