<?php
include_once '../../../namespacesAdress.php';
include_once conexion.'conectionData.php';
include_once datos.'configuracionDatabaseLinker.class.php';

session_start();

$cnfDb = new ConfiguracionDatabaseLinker();

$configuracion = $cnfDb->getConfiguracion($_SESSION['centro']);

header('content-type:text/css');
 
$colorPrincipal = $configuracion->getColor();

echo <<<FINCSS
/* General Blueprint Style */
/*@import url(http://fonts.googleapis.com/css?family=Lato:300,400,700);*/
@font-face {
	font-family: 'fontawesome';
	src:url('../fonts/fontawesome.eot');
	src:url('../fonts/fontawesome.eot?#iefix') format('embedded-opentype'),
		url('../fonts/fontawesome.svg#fontawesome') format('svg'),
		url('../fonts/fontawesome.woff') format('woff'),
		url('../fonts/fontawesome.ttf') format('truetype');
	font-weight: normal;
	font-style: normal;
}

*, *:after, *:before { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
body, html { font-size: 100%; padding: 0; margin: 0;}

/* Clearfix hack by Nicolas Gallagher: http://nicolasgallagher.com/micro-clearfix-hack/ */
.clearfix:before, .clearfix:after { content: " "; display: table; }
.clearfix:after { clear: both; }

body {
    font-family: 'Lato', Calibri, Arial, sans-serif;
}

a {
	color: #f0f0f0;
	text-decoration: none;
}

a:hover {
	color: #000;
}

.main,
.container > header {
	width: 95%;
	max-width: 69em;
	margin: 0 auto;
	padding: 0 1.875em 3.125em;
}

.container > header {
	padding: 2.875em 1.875em 1.875em;
}

.container > header h1 {
	font-size: 2.125em;
	line-height: 1.3;
	margin: 0;
	float: left;
	font-weight: 400;
}

.container > header span {
	display: block;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5em;
	padding: 0 0 0.6em 0.1em;
}

.container > header nav {
	float: right;
}

.container > header nav a {
	display: block;
	float: left;
	position: relative;
	width: 2.5em;
	height: 2.5em;
	background: #fff;
	border-radius: 50%;
	color: transparent;
	margin: 0 0.1em;
	border: 4px solid $colorPrincipal;
	text-indent: -8000px;
}

.container > header nav a:after {
	content: attr(data-info);
	color: $colorPrincipal;
	position: absolute;
	width: 600%;
	top: 120%;
	text-align: right;
	right: 0;
	opacity: 0;
	pointer-events: none;
}

.container > header nav a:hover:after {
	opacity: 1;
}

.container > header nav a:hover {
	background: $colorPrincipal;
}

.icon-drop:before, 
.icon-arrow-left:before {
	font-family: 'fontawesome';
	position: absolute;
	top: 0;
	width: 100%;
	height: 100%;
	speak: none;
	font-style: normal;
	font-weight: normal;
	line-height: 2;
	text-align: center;
	color: $colorPrincipal;
	-webkit-font-smoothing: antialiased;
	text-indent: 8000px;
	padding-left: 8px;
}

.container > header nav a:hover:before {
	color: #fff;
}

.icon-drop:before {
	content: "\e000";
}

.icon-arrow-left:before {
	content: "\f060";
}

.filtroHC {
	position: absolute;
    top: 45px;
    right: 16px;
    font-size: 18px;
}

@media screen and (max-width: 500px) {

    .filtroHC {
        position: relative;
        right: 0px;
        top: 0px;
    }
    
}

FINCSS;
?>