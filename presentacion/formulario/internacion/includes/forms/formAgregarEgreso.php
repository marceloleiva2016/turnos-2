<?php
include_once '../../../../../namespacesAdress.php';
include_once dat_formulario.'formInternacionDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';
include_once datos.'diagnosticoDatabaseLinker.class.php';
include_once negocio.'usuario.class.php';

session_start();

$idformInt = Utils::postIntToPHP($_REQUEST['id']);

$dbInt = new FormInternacionDatabaseLinker();

$destinos = $dbInt->getTiposEgresos($idformInt);

$puede = $dbInt->puedeDarEgreso($idformInt);

$error = false;

if(!$puede->puede)
{
  $error = true;
  $message = $puede->message;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>EGRESO DEMANDA</title>

</head>
<body bgcolor = "#FFFFFF" style="width: 100%; text-align: center;">
<?php
if(!$error)
{
?>
    <script>
        frmOk = true;
    </script>
    <form id="formDatos">
    <div id="divPrincipal" title="Egreso Demanda" style="width: 300px; margin: 0 auto 0 auto">
        <div id="divSalidaDiagno">
          <fieldset>
            <legend><b>Buscar Diagnostico:</b></legend>
            <div>
              Diagnostico:
              <input name="codBusq" id="codBusq" placeholder="Codigo">
              <input name="diagBusq" id="diagBusq" placeholder="Nombre">
              <button name="diagFiltrar" id="diagFiltrar" >Filtrar</button>
            </div>
          </fieldset>
          <fieldset>
            <legend><b>Diagnostico Seleccionado:</b></legend>
            <div>
              Diagnostico:
              <select name="dg_diagnostico" id="dg_diagnostico" >
              </select>
            </div>
          </fieldset>
        </div>
        <br>
        <div id="divSalidaDestino">
          <b>
            Destino:
          </b>
          <br/>
            <?php
                for ($i=0; $i < count($destinos); $i++)
                { 
                    echo "<input type='radio' name='idDestino' value='".$destinos[$i]['id']."' " .(($i==0)?"CHECKED":""). " >".$destinos[$i]['detalle']."</input><br/>";
                }
            ?>  
         </div>
    </div>
    </form>
<?php
}
else
{
?>
    <script>
        frmOk=false;
    </script>
    
    <div id="divPrincipal" title="" style="width: 300px; margin: 0 auto 0 auto">
        <div id="ErrorDiv">
            <?php echo $message;?>
        </div>
    </div>
<?php   
}
?>

    
<script>

var capitulo = "";
var diagnostico = "";

function cargarOptions(combo, datos)
{
  for(i=0; i<datos.length; i++)
  {
    combo.append("<option value='"+datos[i].id+"'>"+ datos[i].descripcion +"</option>");
  }
}

function vaciarComboDiagnostico()
{
  $('#dg_diagnostico option').remove();
}

$(document).ready(function()
{

  $('#diagFiltrar').click(function(event){
    event.preventDefault();
    cod = $('#codBusq').val();
    diag = $('#diagBusq').val();
      vaciarComboDiagnostico();
      $.ajax({
        type:'post',
        dataType:'json',
        url:'includes/json/jsonDiagnosticos.php',
        data:{nombre:diag,codigo:cod},
        success: function(json)
        {
          diagnosticos = json;
          cargarOptions($('#dg_diagnostico'),diagnosticos);
        }
      });
    });

});

</script>
</body>
</html>