<?php
	include_once '../namespacesAdress.php';
	include_once datos.'centroDatabaseLinker.class.php';
	include_once conexion.'conectionData.php';

	$entidad = "TURNOS";
	session_start();

	$centrosDb = new CentroDatabaseLinker();

	$centros = $centrosDb->getCentros(Business);

?>
<html>
<head>
	<title>Turnos</title>
	<link media="screen" type="text/css" rel="stylesheet" href="includes/css/login.php">
	<link media="screen" type="text/css" rel="stylesheet" href="includes/css/barra.php">
	<link media="screen" type="text/css" rel="stylesheet" href="includes/css/iconos.css">
	<link media="screen" type="text/css" rel="stylesheet" href="includes/css/demo.php">
</head>
<body>
	<div class="post">
		<div class="page">
			<div class="logo">
				<h1>Sistema Salud</h1>
			</div>
			<div align="center">
				<form method="post" action="validarInicioSession.php">
					<table>
						<tr>
							<td>
								<select class="select" id="inputCentro" name="centro">
									<?php
									for ($i=0; $i < count($centros); $i++)
									{ 
										echo "<option value='".$centros[$i]->getId()."'>".$centros[$i]->getDetalle()."</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input class="input" id="inputUsuario" name="usuario" placeholder="Nombre Usuario">
							</td>
						</tr>
						<tr>
							<td align="center">
								<input class="input" type="password" id="inputContrasenia" name="contra" placeholder="Password"></br>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input type="hidden" id="entidad" name="entidad" value="<?=$entidad; ?>" />
								<input class="boton" type="submit" id="acceder" value="Acceder" >
							</td>
						</tr>
						<tr>
							<td>
								<?php 
								if (isset($_GET['error']))
								{
								?>
								<div >
									<div class="error">
										<p >Credenciales de Sesion incorrectos</p>
									</div>
								</div>
								<?php
								}
								else if(isset($_GET['logout']))
								{
									unset($_SESSION);
									session_destroy();
									?>
									<div >
									<div class="error">
										<p >El usuario ha terminado su session!</p>
									</div>
									</div>
									<?php
								}
								else
								{
								?>
								<div id="errores" style="clear: both;">
									
								</div>
								<?php
								}
								?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>