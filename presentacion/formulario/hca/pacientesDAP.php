<?php
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
   control_acceso($user_id,$password,$AREA_P,$FUNCION);


include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';

session_start();

$baseDeDatos = new GeneralesDataBaseLinker();
$error = false;


if (isset($_POST['ano']) && !empty($_POST['ano'])) {
	$ano = Utils::postIntToPHP($_POST['ano']);
} else {
	$error = true;
	$message = "<b>Se debe especificar un a&ntilde;o </ br> vuelva a especificar los par&aacute;metros</b>";
}

if (isset($_POST['mes']) && !empty($_POST['mes'])) {
	$mes = Utils::postIntToPHP($_POST['mes']);	
} else {
	$error = true;
	$message = "<b>Se debe especificar un mes </ br> vuelva a especificar los par&aacute;metros</b>";
}

if($error) {
	header('Location: pacientesDAPForm.php');
	//$_SESSION['error'] = $message;	
	exit;
} else {
	unset($_SESSION['error']);
	/*$_SESSION['centro'] = $centro;	
	$_SESSION['ano'] = $ano;	
	$_SESSION['mes'] = $mes;*/		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pacientes DAP</title>

	<link type="text/css" rel="Stylesheet" href="/tools/jquery/css2/jquery-ui-1.8.16.custom.css" />
	<!--  <link type="text/css" rel="Stylesheet" href="includes/css/table.css" />-->
   <!--  <link type="text/css" rel="Stylesheet" href="/tools/jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" />--> 
	<link type="text/css" rel="Stylesheet" href="/tools/jquery/validity/css/jquery.validity.css" />
    
    <link type="text/css" rel="Stylesheet" href="includes/css/compartido.css" />
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/tablesorter/css/style.css" />
    <link type="text/css" rel="Stylesheet" href="/tools/jquery/jqGrid/css/ui.jqgrid.css" />
    
	<style><!--TODO: TODOS ESTOS ESTILOS LOS TENGO QUE SACAR A OTRO ARCHIVO DE ESTILOS-->
	
		#contenido{
			width:800px; 
			float: left; 
			background: #fff; 
			padding: 5px 10px 5px 10px; 
			border-left: solid 1px #000; 
			border-right:solid 1px #000; 
			padding:10px; 
			margin:10px;
		}
		
		.importe {
			text-decoration:underline;
		}
		.importe:hover {
			cursor: pointer; cursor: hand;
			
		}
		#tituloTabla{
			font-family:arial;
			font-size: 12pt;
		}
		body{
		background:url(includes/images/fondo.png) repeat-x top left #eff3ff;
		
		}
	</style>
    

	
    <script src="/tools/jquery/js/jquery-last.min.js" type="text/javascript"></script>
	<script src="/tools/jquery/js/jquery-ui-last.custom.min.js" type="text/javascript"></script>
	  
	<script src="/tools/jquery/jqGrid/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="/tools/jquery/jqGrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    
    <script src="/tools/jquery/flot/js/jquery.flot.min.js" type="text/javascript"></script>
    
    <script type="text/javascript" src="/tools/jquery/validity/js/jquery.validity.js"></script>
	<script src="includes/js/pacientesDAP.js" type="text/javascript"></script>
	
	<script>
		var mes = <?php echo $mes;?>;
		var ano = <?php echo $ano;?>;
	</script>
</head>

<body bgcolor = "#FFFFFF">
<script>
</script>

<div id="barra" >
	<div id = "barraImage" >
	</div>
	<div style="float: left;">
		<p>&nbsp;<a href="/sistemas.php3">Sistema Salud</a>&nbsp;&gt;
		<a href="../busqpacint.php3">Menu Internaciones</a>&nbsp;&gt;
		<a href="pacientesDAPForm.php">Lista Pacientes DAP</a></p>
	</div>
	<div style="float: right; text-align: right; width: 200px; margin-top:-10px; margin-right:10px;">
	    <p>Usuario: <b><?php print $user_id;?></b><br>
	       <a href="#" id="btnCambiarUsuario">Cambiar Usuario</a>
		</p>
	</div>
	
</div>

<table width="100%" align="center" style="position: absolute; top:50px; width: 100%">
	<tr>
    <td>
    
    	<table width="800px" align="center" style="top:40px;" >
			<tr>
   			 <td> 
   			 	<div id="barral" style="clear:both; height:100px;vertical-align:middle;background-color:#A4BCDF; color: white;">
   			 		<br />
   			 		<h2 align="center">Pacientes DAP<br /> </h2>
   			 		
   			 	</div>
		 		<div id="subbarra" style="clear: both; height:20px; vertical-align:middle; background-color:#D3DDED; font-family: arial; color: #717171;">
   			 	  <table cellpadding="0" cellspacing="0" width="100%" border="0">
   			 	    <tr>
   			 	      <td width="150" align="left"><a href="javascript:back();">Volver</a></td>
   			 	      
   			 	      <td width="300" align="right">
   			 	      <b>
   			 	      </b></td>
   			 	    </tr>
   			 	  </table>		 
   		 		</div>
   		 		<?php 
    			if(!$error)
    			{
    			?>
    			<div id="cargando" style="position:relative; left:80px; top:100px; display:none; color: blue;" align="center">
	   			 	<p><img id="loading" src="includes/images/ajax-loader.gif" alt="Cargando..."/>
	   			 	
	   			 	Espere mientras carga</p>
				</div> 
             	
             	<div id="contenido" align="center">
             		<div id="contenidoSuperior" style="clear: both">
	             		
	             		<table id="table" width="100%">
	             		</table>
	                	<div id="pagerTable"></div>
	                	<div id="ptoolbar" ></div>
	                	
	                </div>
	                
                  	<div id ="mandarAExcel" style="clear:both; text-align: right;">
                	<a target="_blank" href="includes/ajaxFunctions/excelPacientesDAP.php">Excel</a>
                	</div>
             		
                </div>
                
                
                <?php
		    	}
		    	else 
		    	{
				?>	
				<div id="errorDiv" align="center" style="width:350px; margin: 0 auto 0 auto; ">	
					<table id="errorTable" style="border:1px solid #000000;	border-spacing: 0px;margin: 10px">
						<thead>
						<tr>
							<th style="font-family: Arial;font-weight: bold;height: 21px;background: #800000;">
							Error!
							</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td style="font-family: Arial, Helvetica, sans-serif; font-weight:normal; color: #000000;text-align: center;">
								<?php echo $message?>
								</td>
							</tr>
						</tbody>
						
					</table>
				</div>
				<?php 
		    	}
				?>
                      
              </td>
    		</tr>
    		<tr>
    			<td>
    				
    			</td>
    		</tr>
		</table>
		

		
    </td>
    </tr>
</table>
<form action="formularioDAP.php" method="post" id="frmSeleccionarPaciente" target="_blank">
		<div id="divTabla" title="Seleccionar Paciente" style="width: 800px; margin: 0 auto 0 auto">
			
			<!--  <table id="datosJson" width="100%"></table>
			<div id="pager2"></div>
			<div id="list2"></div>-->
			<input type="hidden" name="id" value="" id="nuevoHCA"/>
			
		</div>
</form>
</body>
</html>