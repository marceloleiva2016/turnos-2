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

body, div ,dl ,dt ,dd ,ul ,ol ,li ,h1 ,h2 ,h3 ,h4 ,h5 ,h6 ,pre ,form ,textarea ,p ,blockquote
{
    margin:0;
    padding:0;
}
html, body
{
	margin-top: 20px;
	background-image: url(../../includes/images/pattern.png);
}

/* Barra */
#barra
{
	position: fixed;
	clear:both;
	top:0;
	left:0;
	font-size: 10pt;
	width: 100%;
	margin:0px 0px 0px 0px;
	height: 40px;
	background-color: $colorPrincipal;
	color: #DDD;
	z-index: 99;
	font-family: "Josefin Slab",Arial,sans-serif;
}

#barraImage
{
	float:left;
	margin-top:9px;
	margin-left:10px;
	width: 20px;
	height: 25px;
}

#barra #navegar
{
	margin:0px 0px 0px 0px;
	padding-top: 11px;
	float: left;
}

#barra a
{
	text-align: left;
	font-weight: bold;
	color: #FFF;
	text-decoration: none;
}

#barra a:LINK
{
	color: #AAA;
	text-decoration: none;
}

#barra a:VISITED
{
	color: #AAA;
	text-decoration: none;
}

#barra a:HOVER
{
	color: #FFF;
	text-decoration: none;
}

#usuarioImage
{
	float:right;
	margin-top:10px;
	margin-left:10px;
	background: url('../img/usuario.icon.png') no-repeat;
	width: 20px;
	height: 25px;
}

#usuario {
	float:right;
	margin-top:10px;
	margin-right:10px;
}

#usuario a
{
	text-align:right;
	
	font-weight: bold;
	color: #FFF;
}

@media screen and (max-width: 400px) {

	#navegar {
		display: none;
	}
	
}

FINCSS;
?>