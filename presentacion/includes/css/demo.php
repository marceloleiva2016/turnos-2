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

body {
	padding: 0;
	margin: 0;
	font-family: sans-serif;
	font-size: 1em;
	line-height: 1.5;
	color: #555;
	background: #fff;
}
h1 {
	font-size: 1.5em;
	font-weight: normal;
}
small {
	font-size: .66666667em;
}
a {
	color: #FFFFFF;
	text-decoration: none;
}

.iconoBlue {
    color: $colorPrincipal;
    text-decoration: none;
}

.bshadow0, input {
	box-shadow: inset 0 -2px #e7e7e7;
}
input:hover {
	box-shadow: inset 0 -2px #ccc;
}
input {
	color: inherit;
	line-height: 1.5;
	height: 1.5em;
	padding-right: 5px;
    padding-left: 5px;
    font-size: 1em;
}
input:focus {
	outline: none;
	box-shadow: inset 0 -2px $colorPrincipal;
}

select {
    line-height: 1.4;
    overflow: hidden;
    padding: 0.4em 2.1em 0.4em 1em;
    text-align: left;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
    color: $colorPrincipal;
    font-weight: normal;
    background: none repeat scroll 0 0 #f6f6f6;
    border: 1px solid #c5c5c5;
    position: relative;
	border-bottom-right-radius: 10px;
}
.glyph {
	font-size: 16px;
	width: 15em;
	padding-bottom: 1em;
	margin-right: 4em;
	margin-bottom: 1em;
	float: left;
	overflow: hidden;
}
.liga {
	width: 80%;
	width: calc(100% - 2.5em);
}
.talign-right {
	text-align: right;
}
.talign-center {
	text-align: center;
}
.bgc1 {
	background: #f1f1f1;
}
.fgc1 {
	color: #999;
}
.fgc0 {
	color: #000;
}
p {
	margin-top: 1em;
	margin-bottom: 1em;
}
.mvm {
	margin-top: .75em;
	margin-bottom: .75em;
}
.mtn {
	margin-top: 0;
}
.mtl, .mal {
	margin-top: 1.5em;
}
.mbl, .mal {
	margin-bottom: 1.5em;
}
.mal, .mhl {
	margin-left: 1.5em;
	margin-right: 1.5em;
}
.mhmm {
	margin-left: 1em;
	margin-right: 1em;
}
.mls {
	margin-left: .25em;
}
.ptl {
	padding-top: 1.5em;
}
.pbs, .pvs {
	padding-bottom: .25em;
}
.pvs, .pts {
	padding-top: .25em;
}
.clearfix {
	zoom: 1;
}
.unit {
	float: left;
}
.unitRight {
	float: right;
}
.size1of2 {
	width: 50%;
}
.size1of1 {
	width: 100%;
}
.clearfix:before, .clearfix:after {
	content: " ";
	display: table;
}
.clearfix:after {
	clear: both;
}
.hidden-true {
	display: none;
}
.textbox0 {
	width: 3em;
	background: #f1f1f1;
	padding: .25em .5em;
	line-height: 1.5;
	height: 1.5em;
}
#testDrive {
	padding-top: 24px;
	line-height: 1.5;
}
.fs0 {
	font-size: 16px;
}
.fs1 {
	font-size: 32px;
}

.contenedor{
	
}

.button-secondary {
    color: white;
    border: none;
    border-radius: 4px;
    background: $colorPrincipal; /* this is a light blue */
    height: 40px;
 }

.button-secondary2 {
    background: $colorPrincipal none repeat scroll 0 0;
    border: medium none;
    border-radius: 4px;
    color: white;
    height: 30px;
}

.topright {
    position: absolute;
    top: 45px;
    right: 16px;
    font-size: 18px;
}

fieldset {
    width: 50%;
}

.tabla {
    border-collapse: collapse;
    border: 0px;
}

.tabla th, .tabla td {
    border: 0px;
    padding: 10px;
    text-align: left;
    border-bottom: 0px solid #ddd;
}

.tabla th {
    border: 0px;
    height: 10px;
    text-align: center;
    vertical-align: bottom;
    background-color: $colorPrincipal;
    color: white;
}

.tabla tr:hover {background-color: #f5f5f5}

.tabla tr:nth-child(even) {background-color: #f2f2f2}

.tabla2 {
    border: 0px;
}

.tabla2 th, .tabla2 td {
    border: 0px;
    padding: 5px;
    text-align: center;
    border-bottom: 0px solid #ddd;
}

.tabla2 th {
    border: 0px;
    height: 10px;
    text-align: center;
    background-color: $colorPrincipal;
    color: white;
}

.tabla2 tr:hover {background-color: #f5f5f5}

.tabla2 tr:nth-child(even) {background-color: #f2f2f2}

.contenedorPaciente {
    padding: 10px 20px 10px 20px;
    overflow: hidden;
    width:400px;
    height:auto;
    border-color: black;
    border-width: 0pt;
    border-style: solid;
    background-color:$colorPrincipal;
    color: #fff;
    border-radius: 5px;
}

.subContenedorPaciente{
    contain: content;
}

.datosPaciente {
    margin: 0px 0px 0px 10px;
    float:left;
    top: 10px;
    left: 10px;
}

.imagenSexoPacienteHombre {
    margin: 10px 10px 10px 10px;
    width: 100px;
    height: 100px;
    float: right;
    top:10px;
    left:200px;
    background-image: url(../images/hombre.png);
    background-color:#FFFFFF;
    background-size: contain;
}

.imagenSexoPacienteMujer {
    margin: 10px 10px 10px 10px;
    width: 100px;
    height: 100px;
    float: right;
    top: 10px;
    left: 200px;
    background-image: url(../images/mujer.png);
    background-color:#FFFFFF;
    background-size: contain;
}

.imagenInterrogacion {
    width: 100px;
    height: 100px;
    background-image: url(../images/question_white.png);"
    display:block;
    margin:0 auto 0 auto;
}

.interrogacionMensaje {
    border: 0pt;
    border-color: black;
    border-style: solid;
    width: 400px;
    position: relative;
    left: 10px;
}

.imagenLogoW {
    width: 50px;
    height: 50px;
    display:block;
    margin:0 auto 0 auto;
    background-image: url(../images/logow.png);
    background-size: contain;
    margin-top: 0px;
}

.horariosConsultorios {
    display:inline-block;
    width: 20em;
    overflow: hidden;
}

.horarioContenido {
    height: auto !important;
}

.horarioContenedor {
    width:100%;
    height:100%;
    margin-left: 2%;
    margin-right: 2%;
}

.fechasContenedor{
    width:100%;
    height:100%;
}

.fechasConsultorios {
    padding: 0.5em 0.5em;
    border: 2px solid $colorPrincipal;
    color: $colorPrincipal;
    font-weight: 700;
    width: 100px;
}

.fechasConsultorios a {
    color: #888;
    text-decoration: none;
}

.fechasConsultorios a:HOVER {
    color: #888;
    font-size: 16px;
    cursor:pointer;
}

.contenedorObraSocial {
    padding: 13px 15px 12px 15px;
    margin-left: 20px;
    overflow: hidden;
    width: 400px;
    height:auto;
    border-color: black;
    border-width: 0pt;
    border-style: solid;
    background-color: #d8a600;
    color: #fff;
    border-radius: 5px;
}

.pageOsoc {
    margin: 5px auto 5px auto;
    padding: 5px 5px 5px 5px;
    border: 5px solid rgba(0,0,0,.05);
    box-shadow: 0px 0px 0px 10px rgba(0,0,0,0.03);
    border-radius: 5px;
    text-align: center;
    text-transform: uppercase;
}

.inputsScroll {
    border:2px solid #ccc;
    width: 500px;
    height: 200px;
    overflow-y: scroll;
}

FINCSS;
?>