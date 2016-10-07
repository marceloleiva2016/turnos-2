<?PHP
$FUNCION='C';
$AREA_P='COEXP';
include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//conexion con la base
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
	control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="es"/> 
<title>Listado pacientes DAP</title>

    <link type="text/css" rel="Stylesheet" href="/tools/jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" />
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/validity/css/jquery.validity.css" />
    <link type="text/css" rel="Stylesheet" href="includes/css/compartido.css" />
	
    <script src="/tools/jquery/js/jquery-1.5.1.js" type="text/javascript"></script>
	<script src="/tools/jquery/js/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>   
	<script src="/tools/jquery/validity/js/jquery.validity.js" type="text/javascript"></script>  
</head>
<body>
<?php
include_once '/home/web/namespacesAdress.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
include_once nspcConexion . 'conectionData.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcCommons . 'utils.php';
$baseDeDAtos = new GeneralesDataBaseLinker();

?>

<script>
$(document).ready(function()
{
	$('#contenido').css('left', ($('#tablaInt').width() - $('#contenido').outerWidth())/2);
	$('#login').hide(); 
	$('#login').fadeIn("slow").show();
	//$('#fecha').datepicker("option", "dateFormat", "dd/mm/yy");
	
	
	//ACA viene el validity
	$("form").validity(function() {
	  	$("#ano")                      // The first input:    
	        .require("El año es requerido")
	  
	  
	    $("#mes")                      // The first input:    
	        .require("la fecha es requerida")

	});
});

</script>
<div id="barra" >
	<div id = "barraImage" >
	</div>
	<div style="float: left;">
		<p>&nbsp;<a href="/sistemas.php3">Sistema Salud</a>&nbsp;&gt;
		<a href="../busqpacint.php3">Menu Internaciones</a>&nbsp;&gt;
		<a>Lista Pacientes DAP</a></p>
	</div>
	<div style="float: right; text-align: right; width: 200px; margin-top:-10px; margin-right:10px;">
	    <p>Usuario: <b><?php print $user_id;?></b><br>
	       <a href="#" id="btnCambiarUsuario">Cambiar Usuario</a>
		</p>
	</div>
	
</div>


<table  width="100%" align="center" style="position: absolute; top:50px; width: 100%">
	<tr>
    <td>
    	<table id="tablaInt" width="100%">
			<tr>
   			 <td>     
                
             	<div id="contenido" style="position:relative;width: 500px;">
             	<h2 align="center">LISTADO PACIENTES <br />DAP</h2>
             		<div id="error">
             		<?php
             			if (isset($_SESSION['error'])) {
             				echo 'ERROR: ' . $_SESSION['error'];
             				unset($_SESSION['error']); 
              			} 
             		?>
             		</div>
             		<div id="login" style="background: #97BB95; width: 500px; height: 300px;-moz-border-radius: 10px;background-image:url(./includes/images/Quirofano4.png)">
   			 			<form method="post" action="pacientesDAP.php" style="position:relative; margin: 10px; width: 450px; top:70px">
                    	<p>
                        
                        <br />
                        <b>Seleccione el a&ntilde;o:</b> 
                        <select id = "ano" name="ano">
                         <?php
                        	$anos = $baseDeDAtos->anosConUDP();
                        	for($i=0; $i < count($anos); $i++)
                        	{
                        		$year =$anos[$i]; 
                        		echo "<option value=\"$year\">$year</option>\n";
                        	}
                        ?>
                        	
                        </select>
                        <br />
                        <br />
                        <b>Seleccione el mes:</b> 
                        <select id = "mes" name="mes">
                        	<option value="1">ENERO</option>
                            <option value="2">FEBRERO</option>
                            <option value="3">MARZO</option>
                            <option value="4">ABRIL</option>
                            <option value="5">MAYO</option>
                            <option value="6">JUNIO</option>
                            <option value="7">JULIO</option>
                            <option value="8">AGOSTO</option>
                            <option value="9">SEPTIEMBRE</option>
                            <option value="10">OCTUBRE</option>
                            <option value="11">NOVIEMBRE</option>
                            <option value="12">DICIEMBRE</option>
                        </select>
                         
                        
                        <br /><br />
                        
                     
                        <br />
                        <br />
                        
                        </p>
                        
                        <p align="right">
                        <input type="submit" value="Aceptar" />
                        <br />
                        </p>
                    
                    </form>
   			 	
   			 			
					</div> 
                	
                </div>
                      
              </td>
    		</tr>
		</table>
    </td>
    </tr>
</table>
</body>
</html>