<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcEpicrisis.'epicrisisDatabaseLinker.class.php';

$idEpicrisis = Utils::postIntToPHP($_REQUEST['id']);

$dbEpicrisis = new EpicrisisDatabaseLinker();

$puede = $dbEpicrisis->puedeDarDestino($idEpicrisis);

if($puede->result)
{
  $destinos = $dbEpicrisis->destinosDeEpicrisis($idEpicrisis);

  $error = false;
}
else
{
  $error = true;
}




if($error)
{
  $message = $puede->message;
}
else 
{
    $error = $error || count($destinos)==0;

    if($error)
    {
        $message = "Ya existe una egreso en la epicrisis";
    }
}
?>
<!DOCTYPE html PUBLIC>
<html>
<head>
<title>EGRESO EPICRISIS</title>
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/autocomplete/css/customStyle.css" />
    
    <script type="text/javascript" src="/tools/jquery/numeric/jquery.numeric.js"></script>
    <script type="text/javascript" src="/tools/jquery/jqprint/jquery.jqprint-0.3.js"></script>
    <script src="/tools/jquery/autocomplete/js/jquery.ausu-autosuggest.min.js" type="text/javascript"></script> 
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
    <div id="divPrincipal" title="Egreso Epicrisis" style="width: 300px; margin: 0 auto 0 auto">
        <div id="divSalidaDiagno">
          <b>
            Diagnostico:
          </b>
          <div class="ausu-suggest">
            <input type="text" size="37" value="" name="diagnostico" id="diagnostico" autocomplete="off" />
            <input type="text" size="5" value="" name="diagnosticoid" id="diagnosticoid" autocomplete="off" style="visibility: hidden;"/>
            <input type="hidden" id="cod_diagno" name="cod_diagno" value = ""/>
          </div>
        </div>
        <div id="divSalidaDestino">
          <b>
            Seleccione Destino:
          </b>
          <br/>
            <?php
                for ($i=0; $i < count($destinos); $i++)
                { 
                    echo "<input type='radio' name='idDestino' value='".$destinos[$i]['id']."' " .(($i==1)?"CHECKED":""). " >".$destinos[$i]['detalle']."</input><br/>";
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
$(document).ready(function () {

    $.fn.autosugguest({
              className: 'ausu-suggest',
              methodType: 'POST',
              rtnIDs: true,
              dataFile: '/tools/ajax/hospital/diagnosticos.php',
              minChars: 3
        });

});

</script>
</body>
</html>