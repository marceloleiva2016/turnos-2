<?php

$idturnero = $_REQUEST['id'];

if(!isset($idturnero) OR $idturnero=="" OR $idturnero==null){
    echo "No se encontro configuracion deturnero.";
    die();
}

?>
<html>
<head>
    <title>Pantalla Espera</title>
    <link media="screen" type="text/css" rel="stylesheet" href="includes/css/vista.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.css" />
    <link media="screen" type="text/css" rel="stylesheet" href="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.theme.css" />
    <script type="text/javascript" src="../includes/plug-in/jquery-core-1.11.3/jquery-core.min.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/jquery-ui-1.11.4/jquery-ui.js" ></script>
    <script type="text/javascript" src="../includes/plug-in/ion.sound-3.0.7/ion.sound.min.js" ></script>
    <script type="text/javascript">
        var idturnero = <?php echo $idturnero; ?>;
    </script>
    <script type="text/javascript" src="includes/js/vista.js"></script>
</head>
<body>
<!-- TURNOS NUEVOS -->

<div id="listadoPacientes" >
</div>

<!-- /TURNOS NUEVOS -->
<hr>
<!-- TURNOS VIEJOS -->

<div id="listadoPacientesCaducos" >
</div>


<!-- /TURNOS VIEJOS -->

<div id="consola"></div>
</body>
</html>