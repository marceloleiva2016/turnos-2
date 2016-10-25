<?php
header('content-type:text/css');
 
$colorPrincipal = '#007e47';
 
echo <<<FINCSS

@font-face {
	font-family: 'ecoico';
	src:url('../fonts/timelineicons/ecoico.eot');
	src:url('../fonts/timelineicons/ecoico.eot?#iefix') format('embedded-opentype'),
		url('../fonts/timelineicons/ecoico.woff') format('woff'),
		url('../fonts/timelineicons/ecoico.ttf') format('truetype'),
		url('../fonts/timelineicons/ecoico.svg#ecoico') format('svg');
	font-weight: normal;
	font-style: normal;
} /* Made with http://icomoon.io/ */

.cbp_tmtimeline {
	margin: 30px 0 0 0;
	padding: 0;
	list-style: none;
	position: relative;
} 

/* The line */
.cbp_tmtimeline:before {
	content: '';
	position: absolute;
	top: 0;
	bottom: 0;
	width: 10px;
	background: rgba(17, 30, 55, 0.67);
	left: 20%;
	margin-left: -10px;
}

/* The date/time */
.cbp_tmtimeline > li .cbp_tmtime {
	display: block;
	width: 25%;
	padding-right: 100px;
	position: absolute;
}

.cbp_tmtimeline > li .cbp_tmtime span {
	display: block;
	text-align: right;
}

.cbp_tmtimeline > li .cbp_tmtime span:first-child {
	font-size: 1.9em;
	color: rgba(17, 30, 55, 0.54);
}

.cbp_tmtimeline > li .cbp_tmtime span:last-child {
	font-size: 1em;
	color: #007e47;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmtime span:last-child {
	color: $colorPrincipal;
}

/* Right content */
.cbp_tmtimeline > li .cbp_tmlabel {
	margin: 0 0 15px 25%;
	background: #007e47;
	color: #fff;
	padding-top: 20px;
	padding-left: 30px; 
	padding-right: 30px; 
	padding-bottom: 30px;
	font-size: 1.2em;
	font-weight: 300;
	line-height: 1.4;
	position: relative;
	border-radius: 5px;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel {
	background: $colorPrincipal;
}

.cbp_tmtimeline > li .cbp_tmlabel h2 { 
	margin-top: 0px;
	padding: 0 0 10px 0;
	border-bottom: 1px solid rgba(255,255,255,0.4);
	font-size: 0.9em;
}

/* The triangle */
.cbp_tmtimeline > li .cbp_tmlabel:after {
	right: 100%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
	border-right-color: #007e47;
	border-width: 10px;
	top: 10px;
}

.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
	border-right-color: $colorPrincipal;
}

/* The icons */
.cbp_tmtimeline > li .icon {
	width: 40px;
	height: 40px;
	font-family: 'helium';
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	font-size: 1.4em;
	line-height: 40px;
	-webkit-font-smoothing: antialiased;
	position: relative;
	color: #fff;
	background: $colorPrincipal;
	border-radius: 50%;
	box-shadow: 0 0 0 10px rgba(17, 30, 55, 0.17);
	text-align: center;
	left: 20%;
	top: 0;
	margin: 0 0 0 -25px;
}

/* Example Media Queries */
@media screen and (max-width: 65.375em) {

	.cbp_tmtimeline > li .cbp_tmtime span:last-child {
		font-size: 1.5em;
	}
}

@media screen and (max-width: 47.2em) {
	.cbp_tmtimeline:before {
		display: none;
	}

	.cbp_tmtimeline > li .cbp_tmtime {
		width: 100%;
		position: relative;
		padding: 0 0 20px 0;
	}

	.cbp_tmtimeline > li .cbp_tmtime span {
		text-align: left;
	}

	.cbp_tmtimeline > li .cbp_tmlabel {
		margin: 0 0 30px 0;
		padding: 1em;
		font-weight: 400;
		font-size: 95%;
	}

	.cbp_tmtimeline > li .cbp_tmlabel:after {
		right: auto;
		left: 20px;
		border-right-color: transparent;
		border-bottom-color: #007e47;
		top: -20px;
	}

	.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
		border-right-color: transparent;
		border-bottom-color: $colorPrincipal;
	}

	.cbp_tmtimeline > li .icon {
		position: relative;
		float: right;
		left: auto;
		margin: -55px 5px 0 0px;
	}	
}

FINCSS;
?>