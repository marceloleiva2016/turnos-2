<?php
header('content-type:text/css');
 
$colorPrincipal = '#007e47';
 
echo <<<FINCSS


/* General Blueprint Style */
/*@import url(http://fonts.googleapis.com/css?family=Lato:300,400,700);*/
@font-face {
	font-family: 'bpicons';
	src:url('../fonts/bpicons/bpicons.eot');
	src:url('../fonts/bpicons/bpicons.eot?#iefix') format('embedded-opentype'),
		url('../fonts/bpicons/bpicons.woff') format('woff'),
		url('../fonts/bpicons/bpicons.ttf') format('truetype'),
		url('../fonts/bpicons/bpicons.svg#bpicons') format('svg');
	font-weight: normal;
	font-style: normal;
} /* Made with http://icomoon.io/ */

*, *:after, *:before { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
body, html { font-size: 100%; padding: 0; margin: 0;}

/* Clearfix hack by Nicolas Gallagher: http://nicolasgallagher.com/micro-clearfix-hack/ */
.clearfix:before, .clearfix:after { content: " "; display: table; }
.clearfix:after { clear: both; }

body {
	font-family: 'Lato', Calibri, Arial, sans-serif;
	color: #fff;
	font-weight: 400px;
	min-height: 400px;
}

a {
	color: #f0f0f0;
	text-decoration: none;
}

a:hover {
	color: #A4A4A4;
}

.container > header {
	width: 90%;
	max-width: 69em;
	margin: 0 auto;
	text-align: center;
}

.container > header h1 {
	font-size: 2.125em;
	line-height: 1.3;
	margin: 0 0 0.6em 0;
	float: left;
	font-weight: 400;
}

.container > header > span {
	display: block;
	position: relative;
	z-index: 9999;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5em;
	padding: 0 0 0.6em 0.1em;
}

.container > header > span span:after {
	width: 30px;
	height: 30px;
	left: -12px;
	font-size: 50%;
	top: -8px;
	font-size: 75%;
	position: relative;
}

.container > header > span span:hover:before {
	content: attr(data-content);
	text-transform: none;
	text-indent: 0;
	letter-spacing: 0;
	font-weight: 300;
	font-size: 110%;
	padding: 0.8em 1em;
	line-height: 1.2;
	text-align: left;
	left: auto;
	margin-left: 4px;
	position: absolute;
	color: #fff;
	background: #A4A4A4;
}

.container > header nav {
	float: right;
	text-align: center;
}

.container > header nav a {
	display: inline-block;
	position: relative;
	text-align: left;
	width: 2.5em;
	height: 2.5em;
	background: #fff;
	border-radius: 50%;
	margin: 0 0.1em;
	border: 4px solid #fff;
}

.container > header nav a > span {
	display: none;
}

.container > header nav a:hover:before {
	content: attr(data-info);
	color: #A4A4A4;
	position: absolute;
	width: 600%;
	top: 120%;
	text-align: right;
	right: 0;
	pointer-events: none;
}

.container > header nav a:hover {
	background: #A4A4A4;
}

.bp-icon:after {
	font-family: 'bpicons';
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	text-align: center;
	color: #A4A4A4;
	-webkit-font-smoothing: antialiased;
}

.container > header nav .bp-icon:after {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	line-height: 2;
	text-indent: 0;
}

.container > header nav a:hover:after {
	color: #fff;
}

.bp-icon-next:after {
	content: "\e000";
}
.bp-icon-drop:after {
	content: "\e001";
}
.bp-icon-archive:after {
	content: "\e002";
}
.bp-icon-about:after {
	content: "\e003";
}
.bp-icon-prev:after {
	content: "\e004";
}

@media screen and (max-width: 55em) {

	.container > header h1,
	.container > header nav {
		float: none;
	}

	.container > header > span,
	.container > header h1 {
		text-align: center;
	}

	.container > header nav {
		margin: 0 auto;
	}

	.container > header > span {
		text-indent: 30px;
	}
}

.imagenLogo {
	background-position: 0px;
    background-color: $colorPrincipal;
    background-repeat: no-repeat;
    width: 200px;
    height: 200px;
    display: block;
    margin: 0 auto 0 auto;
    background-image: url(../../includes/images/logow.png);
    background-size: contain;
    margin-top: 50px;
    border-radius: 15px;
}

.imagenLogoLocal {
	background-position: 0px;
	background-image: url(../../includes/images/logo.png);
	background-repeat: no-repeat;
	background-position: 0px;
	width: 88px;
    height: 88px;
    display: inline-block;
}

FINCSS;
?>