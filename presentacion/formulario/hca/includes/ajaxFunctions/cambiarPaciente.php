<?php
//Esta funcionalidad va a cambiar los datos de toda la internacion del paciente actual
//a uno nuevo. Esto es para cuando internan y seleccionan el paciente equivocado
//Si se leen los pasos comentados como PASO X, se explica enteramente el algoritmo
$FUNCION='C';
$AREA_P='PISSMA';

include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';
//header("Cache-Control: private");
//conexion con la base
   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   //TODO: control_acceso($user_id,$cc_password,$AREA_P,$FUNCION);

   
include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcCommons . 'dataBaseConnector.php';
include_once nspcCommons . 'utils.php';
include_once nspcHca . 'hca.class.php';
include_once nspcPacientes . 'paciente.class.php';
include_once 'salida.class.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';

$data->result = true;


//TODO: harcodear el usuario que puede hacer esto
if (!($user_id=="ngonzalez"  || $user_id=="ramireza2"))
{
	errores("Ud. no esta autorizado a realizar esta funcion");
	exit;
}


	$hcaDb = new HcaDatabaseLinker();
	$tipodoc = Utils::postIntToPHP($_POST['tipodoc']);
	$nrodoc = Utils::postIntToPHP($_POST['nrodoc']);
	$idHCA = Utils::postIntToPHP($_POST['id']);
	
	/*@var $pacienteViejo Paciente */
	$hca = new Hca($hcaId);
	$pacienteViejo = $hcaDb->paciente($idHCA);
	
	$data->result = true;
	
	$generalesDb = new GeneralesDataBaseLinker();
	$generalesDb->nombrePaciente($tipoDoc, $nroDoc);
	//PASO 1: verificar la existencia del nuevo paciente y corroborar que no sea el mismo que 
	//ya se encuentra 
	 if(!($generalesDb->existePaciente($tipodoc, $nrodoc)))
	 {
	 	$data->result = false;
	 	$data->message = "El paciente que intenta ingresar no Existe";
	 }
	 else
	 {
	 	if($pacienteViejo->tipoDoc == $tipodoc && $pacienteViejo->nroDoc == $nrodoc)
	 	{
	 		$data->result = false;
	 		$data->message = "El paciente es el mismo que ya se encuentra internado";
	 	}
	 }
	
	$ingresoViejo = $hca->getFechaIngreso();
	$tipoDocViejo = $pacienteViejo->tipoDoc;
	$nroDocViejo = $pacienteViejo->nroDoc;
	
	$baseDeDatos = new dataBaseConnector(HOST,0,DBvieja, "sissalud", "s20s05");
	 //PASO 2: verificar que el paciente no se encuentra internado, y que no tiene una 
	//internacion que se solape con esta
	
	if($data->result)
	{
		
		if($hca->hayEgreso($idHCA))
		{
			/*@var $salida Salida */
			$salida = $hcaDb->salida($idHCA);
			$egresoViejo = $salida->fecha;
			
			//me tengo que fijar si existe una internacion de este paciente
			//que tenga fecha de ingreso entre estas dos fechas
			//o fecha de ingreso anterior al ingreso y sin egreso o egreso posterior al ingreso
			$query = "	select 
							count(*) as cantidad 
						from 
							IEH 
						Where 
							nrodoc = ".Utils::phpIntToSQL($nrodoc)." AND
							tipodoc = ".Utils::phpIntToSQL($tipodoc)." AND
							(	
								(	
									fecha_ingreso >= ".Utils::phpTimestampToSQLDatetime($ingresoViejo)." and
									fecha_ingreso <= ".Utils::phpTimestampToSQLDatetime($egresoViejo)."
								) 
							OR
								(
									fecha_ingreso <= ".Utils::phpTimestampToSQLDatetime($ingresoViejo)." and
									(
										fecha_alta is null or 
										fecha_alta >= ".Utils::phpTimestampToSQLDatetime($ingresoViejo)."
									)
								)
							);";
			
			
		}
		else {
			//Si es una internacion sin egreso entonces tengo que ver que si existe una internacion
			//sin cerrar o alguna internacion con cierre despues del ingreso, en ese caso esta
			//internacion deberia estar cerrada
			
			$query = "	select 
							count(*) as cantidad 
						from 
							IEH 
						Where 
							nrodoc = ".Utils::phpIntToSQL($nrodoc)." AND
							tipodoc = ".Utils::phpIntToSQL($tipodoc)." AND
							(
								fecha_alta is null
							OR
								fecha_alta > ".Utils::phpTimestampToSQLDatetime($ingresoViejo)."
							)
						;";
		}
		
		try {
			$baseDeDatos->conectar();
			$baseDeDatos->ejecutarQuery($query);
		} 
		catch (Exception $e) {
			throw new Exception('Error ejecutando consulta ('. $query .')\n' . $e->getMessage().'', 32143);
		}
		
		$result = $baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		if($cantidad>0)
		{
			$data->result = false;
			$data->message = "El paciente que intenta internar, ya tiene una internacion que se solapa con esta";
		}
	}
	
	//PASO 3: Modificar ieh e himef con el nuevo tipo y nro de documento
	//en ieh tambien hay que actualizar la obra social
	//actualizar hca con el nuevo tipo y numero de documento
	//actualizar incli si la internacion esta sin cerrar el paciente deberia estar
	//ocupando una cama
	if($data->result)
	{
		//Aca voy a tener que registrar si alguno de los actualizar falla para volver atras
		//los anteriores
		$falla = 0;
		
		$query = "SELECT 
					cod_osoc 
				FROM 
					fichasparafacturacion 
				where 
					tipodoc= " . Utils::phpIntToSQL($tipodoc). " and 
					 nrodoc=" . Utils::phpIntToSQL($tipodoc). " ;";
		 
		try {
			$baseDeDatos->conectar();
			$baseDeDatos->ejecutarQuery($query);
		} 
		catch (Exception $e) {
			throw new Exception('Error ejecutando consulta ('. $query .')\n' . $e->getMessage().'', 32143);
		}
		
		//Si no tiene obra entonces es 0
		$cod_osoc = 0;
		if ($baseDeDatos->querySize>0)
		{
			$result = $baseDeDatos->fetchRow();
			$cod_osoc = Utils::sqlIntToPHP($result['cod_osoc']);
		}
		
		//Actualizo IEH
		$sql = "UPDATE IEH set tipodoc = ".Utils::phpIntToSQL($tipodoc).",
				nrodoc = ".Utils::phpIntToSQL($nrodoc)." ,
				cod_osoc = ".Utils::phpIntToSQL($cod_osoc)." where
				nrodoc = ".Utils::phpIntToSQL($nroDocViejo)." and
				tipodoc = ".Utils::phpIntToSQL($tipoDocViejo)." and
				fecha_ingreso = ".Utils::phpTimestampToSQLDatetime($ingresoViejo)."
				;";
		
		try {
			$baseDeDatos->ejecutarAccion($sql);
		} catch (Exception $e) {
			throw new Exception('Error ejecutando consulta ('. $sql .')\n' . $e->getMessage().'', 32143);
			$data->result = false;
			$data->message = "No se pudo actualizar el IEH del paciente";
			$falla = 1;
		}
		
		if($baseDeDatos->querySize==0)
		{
			$data->result = false;
			$data->message = "No se encontró IEH correspondiente al paciente anterior para ser actualizado";
			$falla = 1;
		}
		
		if($data->result)
		{
			//Actualizo himef
			$sql = "UPDATE himef set tipodoc = ".Utils::phpIntToSQL($tipodoc).",
					nrodoc = ".Utils::phpIntToSQL($nrodoc)."  
					where
					nrodoc = ".Utils::phpIntToSQL($nroDocViejo)." and
					tipodoc = ".Utils::phpIntToSQL($tipoDocViejo)." and
					fecha_ingreso = ".Utils::phpTimestampToSQLDatetime($ingresoViejo)."
					;";
			
			try {
				$baseDeDatos->ejecutarAccion($sql);
			} catch (Exception $e) {
				throw new Exception('Error ejecutando consulta ('. $sql .')\n' . $e->getMessage().'', 32143);
				$data->result = false;
				$data->message = "No se pudo actualizar el himef";
				$falla = 2;
			}
			
			if($baseDeDatos->querySize==0)
			{
				$data->result = false;
				$data->message = "No se encontró himef correspondiente al paciente anterior para ser actualizado";
				$falla = 2;
			}
			
		}
		
		if($data->result)
		{
			//Actualizo incli
			$sql = "UPDATE incli set tipodoc = ".Utils::phpIntToSQL($tipodoc).",
					nrodoc = ".Utils::phpIntToSQL($nrodoc)."  
					where
					nrodoc = ".Utils::phpIntToSQL($nroDocViejo)." and
					tipodoc = ".Utils::phpIntToSQL($tipoDocViejo)." and
					fecha_ingreso = ".Utils::phpTimestampToSQLDatetime($ingresoViejo)."
					;";
			
			try {
				$baseDeDatos->ejecutarAccion($sql);
			} catch (Exception $e) {
				throw new Exception('Error ejecutando consulta ('. $sql .')\n' . $e->getMessage().'', 32143);
				$data->result = false;
				$data->message = "No se pudo actualizar el incli";
				$falla = 3;
			}
		}
		$baseDeDatos->desconectar();
	}
	//TODO:
	//FIN: si algun paso mal volver atras cambios
	//si falla volver atras cambios registrar en log
	
	
	//FIN
	if($data->result)
	{
		$data->show =false; //se puede usar para debug o para indicar exito
		$data->message = "Paciente modificado correctamente";
	}
	
	
	echo json_encode($data);
?>