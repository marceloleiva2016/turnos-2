<?php
include_once '../../../../../namespacesAdress.php';
include_once conexion.'conectionData.php';
include_once datos.'configuracionDatabaseLinker.class.php';

session_start();

$cnfDb = new ConfiguracionDatabaseLinker();

$configuracion = $cnfDb->getConfiguracion($_SESSION['centro']);

header('content-type:text/css');
 
$colorPrincipal = $configuracion->getColor();

echo <<<FINCSS

.hoja{
    background-color: #FFF;
    padding: 5px;
    width: 900px;
}

.textarea-style-edicion{
    background-color: rgba(255, 255, 255, 0.2);
    color: #262626;
    padding: 0.3em;
    width: 98%;
    height: 190px;
    z-index: 10;
}

.textarea-style{
    color: #262626;
    border: 1px solid rgba(0, 0, 0, 0.3);
    padding: 0.3em;
    width: 100%;
    height: 50%;
    z-index: 10;
}

.textarea-style-resp{
    color: #262626;
    background-color: rgba(50, 50, 50, 0.2);
    border: 1px solid rgba(0, 0, 0, 0.3);
    height: auto;
    padding: 0.3em;
    width: 98%;
    z-index: 10;
}

.contenedor-ant{
    color: #262626;
    padding: 0.3em;
    width: 100%;
}

.antecedente{
    float: left;
    display: inline-block;
    border: 1px solid rgba(0, 0, 0, 0.3);
    padding: 0.3em;
    width: 33.3%;
    word-break: break-all;
}

.intervenciones{
    float: left;
    display: inline-block;
    border: 1px solid rgba(0, 0, 0, 0.3);
    padding: 0.3em;
    width: 25%;
    word-break: break-all;
}

.cronicaIntervenciones{
    float: left;
    display: inline-block;
    border: 1px solid rgba(0, 0, 0, 0.3);
    padding: 0.3em;
    width: 33.3%;
    word-break: break-all;
}

.examenCompl{
    float: left;
    display: inline-block;
    border: 1px solid rgba(0, 0, 0, 0.3);
    padding: 0.3em;
    width: 33.3%;
    word-break: break-all;
}

.cuerpoEpicrisis{
    text-align: left;
}

.apartado{
    text-align: left;
    float: none;
    display: inline-block;
    width: 100%;
    margin: 5px 0px 0px 0px;
}

.apartado .cargado{
    text-align: center;
}

#contenedorObservaciones{
    border: solid;
    border-width: 1pt; 
    min-height: 150px;
}

.cnt{
    clear: both;
    margin:10px;
    font-family:arial;
    font-size: 11pt;
}

.lstTextos{
    clear: both;
    background: none;
    margin: 5px;
    word-break: break-all;
}

.lstTextos li
{
    list-style: none;
    padding: 13px 15px;
    background: url("../img/skip2.png") no-repeat scroll 0px;
}

.btnEditar
{
    background: none repeat scroll 0 0 $colorPrincipal;
    border-radius: 4px;
    color: white;
    float:right;
}

@media screen and (max-width: 400px) {

    .antecedente {
        width: 100%;
    }

    .intervenciones {
        width: 100%;
    }

    .examenCompl {
        width: 100%;
    }

    .hoja{
        padding: 5px;
        width: 100%;
    }

    section{
        padding: none;
        text-align: justify;
        max-width: none;
        margin: none;
        clear: both;
    }

    .mockup-content {
        margin-top: 0px; 
        margin-left: 0px;
        text-align: center;
    }
        
}

label {
    display: inline;
}
 
.regular-checkbox {
    display: none;
}
 
.regular-checkbox + label {
    background-color: #fafafa;
    border: 1px solid #cacece;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
    padding: 6px;
    border-radius: 3px;
    display: inline-block;
    position: relative;
}
 
.regular-checkbox + label:active, .regular-checkbox:checked + label:active {
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
}
 
.regular-checkbox:checked + label:after {
    content: '\2714';
    font-size: 24px;
    position: absolute;
    top: -10px;
    left: 0px;
    color: $colorPrincipal;
}

.tag {
    font-family: Arial, sans-serif;
    width: 200px;
    position: relative;
    top: 5px;
    font-weight: bold;
    text-transform: uppercase;
    display: block;
    float: left;
}

.cnt {
    clear: both;
    margin: 10px;
    font-family: arial;
    font-size: 11pt;
}

#contenedorFicha {
    border: solid;
    border-width: 1pt;
}

.hor-minimalist-b {
    /*background: none repeat scroll 0 0 #FFFFFF;*/
    border-collapse: collapse;
    font-family: "Lucida Sans Unicode","Lucida Grande",Sans-Serif;
    /*font-size: 12px;*/
    margin: 20px;
    text-align: left;
    
}
.hor-minimalist-b thead th {
    border-bottom: 2px solid #6678B1;
    /*color: #003399;*/
    font-size: 14px;
    font-weight: normal;
    padding: 10px 8px;
}

.hor-minimalist-b tfoot th {
    /*color: #003399;*/
    font-size: 11px;
    font-weight: normal;
    padding: 4px 8px;
}

.hor-minimalist-b td {
    border-bottom: 1px solid #CCCCCC;
    /*color: #666699;*/
    padding: 6px 8px;
}
.hor-minimalist-b tbody tr:hover td {
    /*color: #000099;*/
}

#tblFichaPaciente{
    margin:0 auto 0 auto; 
    width: 100%;
    text-align: left !important;
}

FINCSS;
?>