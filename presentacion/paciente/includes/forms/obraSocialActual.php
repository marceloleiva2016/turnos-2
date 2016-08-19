 <?php
include_once '../../../../namespacesAdress.php';
include_once datos.'obraSocialDatabaseLinker.class.php';

$obsocDB = new ObraSocialDatabaseLinker();

$tipodoc = $_REQUEST['tipodoc'];
$nrodoc = $_REQUEST['nrodoc'];

$obras_social = $obsocDB->getObraSocialPaciente($tipodoc, $nrodoc);

?>
<script type="text/javascript">
$(document).ready(function() {
       $("#modificarOsoc").click(function(event){
              event.preventDefault();
              var idosoc = $("#idFichaOsoc").val();
              $("#dialog_subcontent").load("includes/forms/modificarObraSocial.php",{id:idosoc, tipodoc:<?php echo $tipodoc; ?>, nrodoc:<?php echo $nrodoc; ?>});
       });
})
</script>
<input type="hidden" id="idFichaOsoc" value="<?php echo $obras_social['id']; ?>">
<label>Obra Social :</label><?php echo $obras_social['obra_social']; ?><br>
<label>Nro de Afiliado :</label><?php echo $obras_social['nro_afiliado']; ?><br>
<label>Empresa :</label><?php echo $obras_social['empresa_nombre']; ?><br>
<label>Direccion :</label><?php echo $obras_social['empresa_direccion']; ?><br>
<label>Fecha de emision :</label><?php echo Utils::sqlDateToHtmlDate($obras_social['fecha_emision']); ?><br>
<label>Fecha de vencimiento :</label><?php echo Utils::sqlDateToHtmlDate($obras_social['fecha_vencimiento']); ?><br>
<label>Ultima Modificacion</label><br>

<?php echo Utils::sqlDateToHtmlDate($obras_social['fecha_creacion']); ?><br>

<button class="button-secondary" id="modificarOsoc" data-dialog="somedialog" ><?php
       if($obras_social==null){
              echo "Ingresar";
       } else {
              echo "Modificar";
       }?></button>


       <script>
      (function() {

        //dialogo de laboratorio
        var dlgtrigger = document.querySelector( '[data-dialog]' ),

          somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog' ) ),

          dlg = new DialogFx( somedialog );

        dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );

        })();
    </script>