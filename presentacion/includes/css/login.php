<?php
include_once '../../../namespacesAdress.php';
include_once conexion.'conectionData.php';
include_once datos.'configuracionDatabaseLinker.class.php';

session_start();

$cnfDb = new ConfiguracionDatabaseLinker();

if(isset($_SESSION['centro']))
{
	$configuracion = $cnfDb->getConfiguracion($_SESSION['centro']);	
}
else
{
	$configuracion = $cnfDb->getConfiguracion(0);
}

header('content-type:text/css');
 
$colorPrincipal = $configuracion->getColor();
 
echo <<<FINCSS

body 
{
	margin: 0;
	padding: 0;
	font-family: 'Open Sans', sans-serif;
	font-size: 13px;
	color: #3B3B3B;
	text-align: center;
	width: 100%;
}

h1, h2, h3 
{
	margin: 0px;
	padding: 0px;
	letter-spacing: -2px;
	text-transform: capitalize;
	font-weight: normal;
	color: #FFF;
}

h1 
{
	font-size: 2em;
	text-align:center;
}

/* Logo */

.logo 
{
	padding-top: 20px;
	margin: 0px;
	color: #FFFFFF;
	text-align: center;
}


.logo h1 
{
	margin: 0px;
	text-transform: lowercase;
	font-size: 3.8em;
	color: #FFFFFF;
}

.logo a 
{
	border: none;
	background: none;
	text-decoration: none;
	color: $colorPrincipal;
}

/* Page */
.page 
{
	margin: 50px auto;
	text-align: center;
	height: 350px;
	width: 500px;
	background: $colorPrincipal;
	border: 1px solid rgba(0,0,0,.05);
	box-shadow: 0px 0px 0px 20px rgba(0,0,0,0.03);
}

.page .form
{
	text-align: center;
}

.error
{
	margin-top: 10px;
	text-align: center;
	padding :10px 10px 10px 10px;
	background: #F08080;
	box-shadow: 0px 0px 0px 10px rgba(0,9,15,0.13);
}

.input
{
	width: 210px;
	height: 45;
	outline: medium none;
	background: none repeat scroll 0% 0% #F7F7F7;
	border: 1px solid #D7D0C0;
	padding: 10px;
	text-transform: lowercase;
	font-family: "Open Sans",sans-serif;
	color: #454545;
}

.boton
{
	background: none repeat scroll 0% 0% #F7F7F7;
	text-transform: uppercase;
	font-size: 15px;
	color: $colorPrincipal;
	margin-top: 20px;
	width: 210px;
	height: 49px;
}
FINCSS;
?>