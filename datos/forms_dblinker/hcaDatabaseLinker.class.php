<?php

include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once neg_formulario.'hca/laboratorio.class.php';
include_once neg_formulario.'hca/altaComplejidad.class.php';
include_once neg_formulario.'hca/rayos.class.php';
include_once neg_formulario.'hca/observacion.class.php';
include_once neg_formulario.'hca/interconsulta.class.php';
include_once neg_formulario.'hca/pendiente.class.php';
include_once neg_formulario.'hca/salidaUDP.class.php';
include_once neg_formulario.'hca/salidaHS.class.php';
include_once neg_formulario.'hca/salida.class.php';
include_once neg_formulario.'hca/tipoHCA.class.php';
include_once neg_formulario.'hca/tipoObservacion.class.php';

include_once negocio.'paciente.class.php';
include_once negocio.'internacion.class.php';

//require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
require_once 'Spreadsheet/Excel/Writer.php';

 

class HcaDatabaseLinker {
	var $baseDeDatos;
	var $firephp;
	
	function HcaDatabaseLinker() {
		$this->baseDeDatos = new dataBaseConnector(HOST,0,DBvieja, "sissalud", "s20s05");
		$this->firephp = FirePHP::getInstance(true);
		session_start();
	}
	
	/**
	 * 
	 * Devuelve el IdInternacion asociado a la HCA
	 * @param int $idHca
	 * @throws Exception
	 * @return IdInternacion
	 */
	function getIdInternacion($idHca)
	{
		
		$sql = "Select 
					tipodoc,
					nrodoc,
					fecha_ingreso
				From
					hca
				where
					id = ".Utils::phpIntToSQL($idHca).";";
		
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$tipoDoc = Utils::sqlIntToPHP($result['tipodoc']);
		$nroDoc = Utils::sqlIntToPHP($result['nrodoc']);
		$fechaIngreso = Utils::sqlDateTimeToPHPTimestamp($result['fecha_ingreso']);
		
		$idInternacion = new IdInternacion();
		$idInternacion->tipoDoc = $tipoDoc;
		$idInternacion->nroDoc = $nroDoc;
		$idInternacion->fechaIngreso = $fechaIngreso;
		
		
		$this->baseDeDatos->desconectar();
		return $idInternacion;
		
	}

	/**
	 * 
	 * Devuelve el Id de la HCA
	 * @param int $fechaCreacion
	 * @param int $tipoDoc
	 * @param int $nroDoc 
	 * @throws Exception
	 * @return int 
	 */
	function getIdHCA($fechaCreacion, $tipoDoc, $nroDoc)
	{
		$sql = "SELECT
					id 
				FROM
					hca
				WHERE
					tipodoc = ".Utils::phpIntToSQL($tipoDoc)." AND
					nrodoc = ".Utils::phpIntToSQL($nroDoc)." AND
					fecha_ingreso = ".Utils::phpTimestampToSQLDatetime($fechaCreacion).";";

		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la HCA", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$id = Utils::sqlIntToPHP($result['id']);
		
		//$this->baseDeDatos->desconectar();
		return $id;
	}

	/**
	 * 
	 * Devuelve el Id de la UGCAM asociada
	 * @param int $hcaId
	 * @throws Exception
	 * @return int 
	 */
	function getIdUGCAM($hcaId)
	{
		$sql = "SELECT
					ugcam_form_id AS id 
				FROM
					hca
				WHERE
					id = ".Utils::phpIntToSQL($hcaId).";";

		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la HCA", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$id = Utils::sqlIntToPHP($result['id']);
		
		$this->baseDeDatos->desconectar();
		return $id;
	}
	
	
	/**
	 * 
	 * Devuelve TRUE si existe la HCA para los parametros indicados
	 * @param int $fechaIngreso 
	 * @param int $tipoDoc
	 * @param int $nroDoc 
	 * @throws Exception
	 * @return bool
	 */
	function existeHCA($fechaIngreso, $tipoDoc, $nroDoc)
	{
		$cant = 0;
		
		$sql = "SELECT
					COUNT(*) AS cantidad 
				FROM
					hca
				WHERE
					tipodoc = ".Utils::phpIntToSQL($tipoDoc)." AND
					nrodoc = ".Utils::phpIntToSQL($nroDoc)." AND
					fecha_ingreso = ".Utils::phpTimestampToSQLDatetime($fechaIngreso).";";
	
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la HCA (".$e->getMessage().")", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$cant = Utils::sqlIntToPHP($result['cantidad']);
		
	//	$this->baseDeDatos->desconectar();
		return ($cant>0);
	}

	
	/**
	 * 
	 * Devuelve TRUE si existe la UGCAM asociada al HCA 
	 * @param int $hcaId 
	 * @throws Exception
	 * @return bool
	 */
	function existeUGCAM($hcaId)
	{
		$cant = 0;
		
		$sql = "SELECT
					COUNT(*) AS cantidad 
				FROM
					hca
				WHERE
					id = ".Utils::phpIntToSQL($hcaId)." AND
					ugcam_form_id is not null;";
	
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la HCA (".$e->getMessage().")", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cant = Utils::sqlIntToPHP($result['cantidad']);
		
		$this->baseDeDatos->desconectar();
		return ($cant>0);
	}
	
	
	
	function tipoHca($idHca)
	{
		$cant = 0;
		
		$sql = "SELECT
					id,
					tipo_hca_id 
				FROM
					hca
				WHERE
					id = ".Utils::phpIntToSQL($idHca).";";
	
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		} 
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la HCA (".$e->getMessage().")", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$tipo = Utils::sqlIntToPHP($result['tipo_hca_id']);
		
	//	$this->baseDeDatos->desconectar();
		return ($tipo);
	}
	
	function paciente($idHca)
	{
		$paciente = new Paciente();
		
		$sql = "Select 
					tipodoc,
					nrodoc
				From
					hca
				where
					id = ".Utils::phpIntToSQL($idHca).";";
		
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		$tipoDoc = Utils::sqlIntToPHP($result['tipodoc']);
		$nroDoc = Utils::sqlIntToPHP($result['nrodoc']);
		
		$paciente->cargarPaciente($tipoDoc, $nroDoc);
		$this->baseDeDatos->desconectar();
		return $paciente;
		
	}
	
	private function queryObservacionesTipo($idHca, $tipo)
	{
		$sql = "select 
					id,
					observacion,
					fecha_creacion as fecha,
					usid
				from 
					hca_observaciones
				where 
					hca_id = ".Utils::phpIntToSQL($idHca)." and
					hca_tipo_observacion = ". Utils::phpIntToSQL($tipo)." and
					estado = 'ACTIVO' ;";
		
		return $sql;
	}
	
	/**
	 * 
	 * retorna de que tipo es la observacion pasada por el id
	 * @param int $idObservacion
	 * @throws Exception
	 */
	function tipoObservacion($idObservacion)
	{
		$query = "Select 
						hca_tipo_observacion as tipo
					from
						hca_observaciones
					Where
						id = " .Utils::phpIntToSQL($idObservacion)." ;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar query: $query", 1122);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$tipo = Utils::sqlIntToPHP($result["tipo"]);
		
		$this->baseDeDatos->desconectar();
		
		return $tipo;
	}
	
	/**
	 * 
	 * Retorna el usuario que creo la observacion
	 * @param int $idObservacion
	 * @throws Exception
	 */
	function usuarioObservacion($idObservacion)
	{
		$query = "Select 
						usid as usuario
					from
						hca_observaciones
					Where
						id = " .Utils::phpIntToSQL($idObservacion)." ;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar query: $query", 1122);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$usuario = $result["usuario"];
		
		$this->baseDeDatos->desconectar();
		
		return $usuario;
	}
	
	
	function obtenerObservacion($idObservacion)
	{
		$sql = "select 
					id,
					observacion,
					hca_tipo_observacion as tipo,
					fecha_creacion as fecha,
					usid
				from 
					hca_observaciones
				where 
					id = ".Utils::phpIntToSQL($idObservacion)." ;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		} catch (Exception $e) {
			throw new Exception("No se pudo conectar y ejecutar la consulta: $sql", 1211);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$this->baseDeDatos->desconectar();
		
		$tipo = Utils::sqlIntToPHP($result['tipo']);
		
		switch ($tipo) {
			case TipoObservacion::OBSERVACION:
				$observacion = new Observacion($result['observacion']);
			break;
			
			case TipoObservacion::INTERCONSULTA:
				$observacion = new Interconsulta($result['observacion']);
			break;
			
			case TipoObservacion::PENDIENTE:
				$observacion = new Pendiente($result['observacion']);
			break;
			
			default:
				;
			break;
		}
		
		$observacion->id = Utils::sqlIntToPHP($result['id']);
		$observacion->usuario = $result['usid'];
		$observacion->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha']);
		
		return $observacion;
		
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param int $idHca
	 * @param Observacion $obs
	 * @param string $comentario
	 * @param string $usid
	 * @param string $usidm
	 */
	function editarObservacion($idHca,Observacion $obs, $comentario, $usidm)
	{
		$sql = "INSERT into 
				hca_observaciones (
					observacion,
					predecesor,
					
					hca_tipo_observacion,
					estado,
					usid,
					usuariom,
					hca_id,
					fecha_creacion ) VALUES
				(
					".Utils::phpStringToSQL($obs->descripcion)." ,
					".Utils::phpIntToSQL($obs->id)." ,
					".Utils::phpIntToSQL($obs->tipoObservacion)." ,
					'ACTIVO',
					".Utils::phpStringToSQL($obs->usuario)." ,
					".Utils::phpStringToSQL($usidm)." ,
					".Utils::phpIntToSQL($idHca)." ,
					now()
				)";
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($sql);
		} catch (Exception $e) {
			throw new Exception("Error intentando crear la nueva observacion" , 0001);
			return false;
		}
		
		//si todo salio bien actualizo la anterior observacion y la pongo como modificada
		$sql = "Update 
				hca_observaciones set
					estado = 'MODIFICADO',
					usuariom = ".Utils::phpStringToSQL($usidm).",
					comentario = ".Utils::phpStringToSQL($comentario)." 
				Where
					id = ".Utils::phpIntToSQL($obs->id)." 
					;";
		
		try {
			$this->baseDeDatos->ejecutarAccion($sql);
		} catch (Exception $e) {
			throw new Exception("Error intentando modificar la vieja observacion" , 0002);
			return false;
		}
		$this->baseDeDatos->desconectar();
		return true;
		
	}
	
	function eliminarSalida(Salida $sal,$comentario,$usidm)
	{
		$sql = "Update 
				hca_salida set
					estado = 'ELIMINADO',
					usuariom = ".Utils::phpStringToSQL($usidm).",
					comentario = ".Utils::phpStringToSQL($comentario)." 
				Where
					id = ".Utils::phpIntToSQL($sal->id)." 
					;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($sql);
		} catch (Exception $e) {
			throw new Exception("Error intentando eliminar la vieja observacion" , 0002);
			return false;
		}
		$this->baseDeDatos->desconectar();
		return true;
	}
	
	function eliminarObservacion(Observacion $obs,$comentario,$usidm)
	{
		$sql = "Update 
				hca_observaciones set
					estado = 'ELIMINADO',
					usuariom = ".Utils::phpStringToSQL($usidm).",
					comentario = ".Utils::phpStringToSQL($comentario)." 
				Where
					id = ".Utils::phpIntToSQL($obs->id)." 
					;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($sql);
		} catch (Exception $e) {
			throw new Exception("Error intentando eliminar la vieja observacion" , 0002);
			return false;
		}
		$this->baseDeDatos->desconectar();
		return true;
	}
	
	function observaciones($idHca)
	{
		$sql = $this->queryObservacionesTipo($idHca, TipoObservacion::OBSERVACION);
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$obs = new Observacion($result['observacion']);
			
			$obs->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha']);
			$obs->usuario = ($result['usid']);
			$obs->id = Utils::sqlIntToPHP($result['id']);
			$ret[] = $obs;
		}
		
		return $ret;
		
	}
	
	function pendientes($idHca)
	{
		$sql = $this->queryObservacionesTipo($idHca, TipoObservacion::PENDIENTE);
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$obs = new Pendiente($result['observacion']);
			$obs->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha']);
			$obs->usuario = ($result['usid']);
			$obs->id = Utils::sqlIntToPHP($result['id']);
			$ret[] = $obs;
		}
		
		return $ret;
	}
	
	function interconsultas($idHca)
	{
		$sql = $this->queryObservacionesTipo($idHca, TipoObservacion::INTERCONSULTA);
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$obs = new Interconsulta($result['observacion']);
			$obs->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha']);
			$obs->usuario = ($result['usid']);
			$obs->id = Utils::sqlIntToPHP($result['id']);
			$ret[] = $obs;
		}
		
		return $ret;
	}
	
	function altaComplejidadSinHacer($idHca)
	{
		$sql = "select 
					id,
					nombre,
					descripcion  
			from 
				hca_lista_alta_complejidad
			where 
				not 
					(id in (select 
							hca_lista_alta_complejidad_id 
							from 
							hca_alta_complejidad 
							where 
							hca_id = ".Utils::phpIntToSQL($idHca)."));";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$altComplejidad = new AltaComplejidad();
			$altComplejidad->id = Utils::sqlIntToPHP($result['id']);
			$altComplejidad->nombre = htmlentities($result['nombre']);
			$altComplejidad->descripcion = htmlentities($result['descripcion']);
			$ret[$i]=$altComplejidad;
		}
		
		return $ret;
	}
	
	function altaComplejidadHechos($idHca)
	{
		//TODO
		$sql = "select 
					ac.hca_lista_alta_complejidad_id as id,
					ac.value as valor,
					listac.nombre as nombre,
					listac.descripcion as descripcion
				from 
					hca_alta_complejidad ac left join
					hca_lista_alta_complejidad listac ON (ac.hca_lista_alta_complejidad_id = listac.id)
				WHERE 
					ac.hca_id = ".Utils::phpIntToSQL($idHca)." AND
					listac.favorito > 0
				Order by listac.favorito asc;";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$ac = new AltaComplejidad();
			$ac->id = Utils::sqlIntToPHP($result['id']);
			$ac->nombre = htmlentities($result['nombre']);
			$ac->descripcion = htmlentities($result['descripcion']);
			$ac->valor = Utils::sqlBoolToPHP($result['valor']);
			$ret[$i]=$ac;
		}
		$this->baseDeDatos->desconectar();
		return $ret;
	}
	
	function rayosSinHacer($idHca)
	{
		$sql = "select 
					id,
					nombre,
					descripcion 
				from 
					hca_lista_rx 
				where 
					not 
						(id in (select 
								hca_lista_rx_id 
								from 
								hca_rx 
								where 
								hca_id = ".Utils::phpIntToSQL($idHca)."
								));";
		//$this->firephp->log($sql, 'Query Rx Sin Hacer');
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$rayo = new Rayo();
			$rayo->id = Utils::sqlIntToPHP($result['id']);
			$rayo->nombre = htmlentities($result['nombre']);
			$rayo->descripcion = htmlentities($result['descripcion']);
			$ret[$i]=$rayo;
		}
		$this->baseDeDatos->desconectar();
		return $ret;
	}
	
	function rayosHechos($idHca)
	{
		$sql = "select 
					rx.hca_lista_rx_id as id,
					rx.value as valor,
					rx.observacion as observacion,
					listrx.nombre as nombre,
					listrx.descripcion as descripcion
				from 
					hca_rx rx left join
					hca_lista_rx listrx ON (rx.hca_lista_rx_id = listrx.id)
				WHERE 
					rx.hca_id = ".Utils::phpIntToSQL($idHca)." AND
					listrx.favorito > 0
				Order by listrx.favorito asc;";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$rayo = new Rayo();
			$rayo->id = Utils::sqlIntToPHP($result['id']);
			$rayo->nombre = htmlentities($result['nombre']);
			$rayo->descripcion = htmlentities($result['descripcion']);
			$rayo->observacion =  htmlentities($result['observacion']);
			$rayo->valor = Utils::sqlBoolToPHP($result['valor']);
			$ret[$i]=$rayo;
		}
		$this->baseDeDatos->desconectar();
		return $ret;
		
		
	}
	
	function laboratoriosHechos($idHca, $tipoLab)
	{
		$sql ="select 
			lab.hca_lista_laboratorio_id as id,
			lab.value as valor,
			lab.text_value as text_value,
			listLab.es_numerico as es_numerico,
			listLab.nombre as nombre,
			listLab.descripcion as descripcion,
			listLab.tipo_laboratorio  as tipo
		from 
			hca_laboratorios lab left join
			hca_lista_laboratorio listLab ON (lab.hca_lista_laboratorio_id = listLab.id)
		WHERE 
			lab.hca_id = ".Utils::phpIntToSQL($idHca)." AND
			listLab.tipo_laboratorio = ". Utils::phpIntToSQL($tipoLab).";";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$laboratorio = new Labortatorio();
			$laboratorio->id = Utils::sqlIntToPHP($result['id']);
			$laboratorio->nombre = htmlentities($result['nombre']);
			$laboratorio->descripcion = htmlentities($result['descripcion']);
			$laboratorio->esNumerico = Utils::sqlBoolToPHP($result['es_numerico']);
			if($laboratorio->esNumerico )
			{
				$laboratorio->valor = Utils::sqlFloatToPHP($result['valor']);
			}
			else 
			{
				$laboratorio->valor = $result['text_value'];
			}
			
			$ret[$i]=$laboratorio;
		}
		
		return $ret;
	}
	
	function esLaboratorioNumerico($idLab)
	{
		$sql = "select 
					lab.es_numerico as es_numerico
				from 
					hca_lista_laboratorio lab
				where 
					lab.id = ".Utils::phpIntToSQL($idLab). "
					;";
		
		//$this->firephp->log($sql);
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta: " . $sql, 201230);
		}
		
		$ret = true;
		if($this->baseDeDatos->querySize()>0)
		{
			$result = $this->baseDeDatos->fetchRow();
			$ret = Utils::sqlBoolToPHP($result['es_numerico']);
		}
		
		return $ret;
	}
	
	function laboratoriosSinHacer($idHca, $tipoLab)
	{
		$sql = "select 
					lab.id as id,
					lab.nombre as nombre,
					lab.descripcion as descripcion,
					lab.es_numerico as es_numerico
				from 
					hca_lista_laboratorio lab
				where 
					not 
						(lab.id in (select 
								hca_lista_laboratorio_id 
								from 
								hca_laboratorios 
								where 
								hca_id = ".Utils::phpIntToSQL($idHca)."
								))
					and lab.favorito > 0
					and lab.tipo_laboratorio = ". Utils::phpIntToSQL($tipoLab)."
					Order By favorito asc;";
		
		//$this->firephp->log($sql);
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta: " . $sql, 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$laboratorio = new Labortatorio();
			$laboratorio->id = Utils::sqlIntToPHP($result['id']);
			$laboratorio->nombre = htmlentities($result['nombre']);
			$laboratorio->descripcion = htmlentities($result['descripcion']);
			$laboratorio->esNumerico = Utils::sqlBoolToPHP($result['es_numerico']);
			$ret[$i]=$laboratorio;
		}
		
		return $ret;
		
	}
	
	
	function hayLaboratoriosSinHacer($idHca)
	{
		$sql = "select 
					count(*) as cantidad
				from 
					hca_lista_laboratorio 
				where 
					not 
						(id in (select 
								hca_lista_laboratorio_id 
								from 
								hca_laboratorios 
								where 
								hca_id = ".Utils::phpIntToSQL($idHca)."
								))
					and favorito > 0
					Order By favorito asc;";
		
		//$this->firephp->log($sql);
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		
		
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad= Utils::sqlIntToPHP($result['cantidad']);
		return $cantidad>0;
		
	}

	function cargarDatosHCA(Hca &$hca)
	{
		
		$sql = "Select 
					fecha_creacion,
					usid,
					tipodoc,
					nrodoc,
					fecha_ingreso,
					motivo_consulta,
					fecha_egreso,
					cod_diagno_egreso,
					cod_profes_egreso,
					fecha_cierre,
					usid_cierre,
					tipo_destino,
					tipo_hca_id,
					cod_diagno_ingreso,
					interv_policial,
					cod_osoc
				From
					hca
				where
					id = ".Utils::phpIntToSQL($hca->getId()).";";
		
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion de la HCA", 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		//$this->firephp-log($this->firephp->log($sql);)
		
		$hca->fechaCreacion = Utils::sqlDateTimeToPHPTimestamp($result['fecha_creacion']);
		$hca->usuario = $result['usid'];
		
		$hca->motivoConsulta = $result['motivo_consulta'];
		$hca->fechaEgreso = Utils::sqlDateTimeToPHPTimestamp($result['fecha_egreso']);
		$hca->diagnosticoEgreso = Utils::sqlIntToPHP($result['cod_diagno']);
		$hca->profesionalEgreso = Utils::sqlIntToPHP($result['cod_profes_egreso']);
		$hca->fechaCierre = Utils::sqlDateTimeToPHPTimestamp($result['fecha_cierre']);
		$hca->usuarioCierre = $result['usid_cierre'];
		$hca->tipoDestino = Utils::sqlIntToPHP($result['tipo_destino']);
		$hca->tipoHCA = Utils::sqlIntToPHP($result['tipo_hca_id']);
		$hca->diagnosticoIngreso = Utils::sqlIntToPHP($result['cod_diagno_ingreso']);
		$hca->obraSocial = Utils::sqlIntToPHP($result['cod_osoc']);
		$hca->intervPolicial = Utils::sqlBoolToPHP($result['interv_policial']);
		
		$fechaIngreso = Utils::sqlDateTimeToPHPTimestamp($result['fecha_ingreso']);
		$nrodoc = Utils::sqlIntToPHP($result['nrodoc']);
		$tipodoc = Utils::sqlIntToPHP($result['tipodoc']);
		$hca->internacion = new Internacion($tipodoc,$nrodoc,$fechaIngreso);
		
		$newQuery =" select
					if(hstd.nombre is NULL,ifnull(sfi.cod_secfis, sfs.cod_secfis),sfs.cod_secfis) as cod_secfis
					from
					hca left join 
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id)left join
					incli ic on (ic.tipodoc = hca.tipodoc and ic.nrodoc = hca.nrodoc and ic.fecha_ingreso = hca.fecha_ingreso) left join
					insecfis sfs on (hs.cod_secfis = sfs.cod_secfis) left join
					insecfis sfi on (ic.cod_secfis = sfi.cod_secfis)
					where
					hca.id = ".Utils::phpIntToSQL($hca->getId()).";";
		try
		{
			$this->baseDeDatos->ejecutarQuery($newQuery);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion de la HCA " . $newQuery, 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$hca->codSectorActual = Utils::sqlIntToPHP($result['cod_secfis']);
		
		$this->baseDeDatos->desconectar();
	}
	
	/*
	function listaDestinosUDP()
	{
		$query = "select 
					id,
					nombre
				  from
				  	hca_tipo_destino
				  order by id asc;";
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$ret[Utils::sqlIntToPHP($result['id'])] = $result['nombre'];
		}
		
		return $ret;
	}*/
	
	function listaTipoHCA()
	{
		$query = "select 
					id,
					nombre
				  from
				  	tipo_hca
				  order by id asc;";
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$ret[Utils::sqlIntToPHP($result['id'])] = $result['nombre'];
		}
		
		return $ret;
	}
	
	function listaDestinosHSUDP()
	{
		$query = "select 
					hstd.id,
					hstd.nombre
				  from
				  	hca_salida_tipo_destino hstd left join
				  	tipo_hca th ON (hstd.tipo_hca_id = th.id)
				  Where 
				  	th.id = 1
				  order by id asc;";
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$ret[Utils::sqlIntToPHP($result['id'])] = $result['nombre'];
		}
		
		return $ret;
	}
	
	function listaDestinosHSDAP()
	{
		$query = "select 
					hstd.id,
					hstd.nombre
				  from
				  	hca_salida_tipo_destino hstd left join
				  	tipo_hca th ON (hstd.tipo_hca_id = th.id)
				  Where 
				  	th.id = 2 
				  order by hstd.id asc;";
		
		//$this->firephp->log($query, "Aca estoy");
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo traer la informacion del ticket", 201230);
		}
		$ret = array();
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$ret[Utils::sqlIntToPHP($result['id'])] = $result['nombre'];
		}
		
		return $ret;
	}
	
	/**
	 * 
	 * Retorna la salida UDP de la internacion, es requisito que exista
	 * @param int $idHCA
	 * @return SalidaUDP
	 */
	/*
	function salidaUDP($idHCA)
	{
		$ret = new SalidaUDP();
		
		$query = "select 
					hc.fecha_egreso,  
					hc.cod_diagno_egreso as diagno,
					hc.cod_profes_egreso as profesional,
					hc.tipo_destino as destino
				from 
					hca hc
				Where
					hc.id =" . Utils::phpIntToSQL($idHCA) ."
				;";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta: $query" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		switch (Utils::sqlIntToPHP($result['destino'])) {
			case 1:
				$ret->destino = TipoDestinoUDP::HSALTARECOMENDADA;
				break;
			
			case 2:
				$ret->destino = TipoDestinoUDP::HSINTERNACION;
				break;	
			
			case 3:
				$ret->destino = TipoDestinoUDP::TERAPIA;
				break;

			case 4:
				$ret->destino = TipoDestinoUDP::QUIROFANO;
				break;
			
			case 5:
				$ret->destino = TipoDestinoUDP::OBITO;
				break;
				
			default:
				throw new Exception("Out of range exception",2412);
			break;
		}
		
		$ret->diagnostico = Utils::sqlIntToPHP($result['diagno']);
		$ret->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha_egreso']);
		$ret->profesional = Utils::sqlIntToPHP($result['profesional']);
		
		
		
		return $ret;
	}
	*/
	/**
	 * 
	 * Retorna la salida HS de la internacion, es requisito que exista
	 * @param int $idHCA
	 * @return SalidaHS
	 */
	function salidaHSDAP($idHCA)
	{
		$ret = new SalidaHS();
		
		$query = "select 
					hs.fecha_creacion,  
					hs.cod_diagno as diagno,
					hs.cod_profes as profesional,
					hs.tipo_destino_id as destino,
					hs.cod_centro as centro,
					hs.cod_servicio as servicio,
					hs.cod_secfis as sector,
					hs.id,
					hs.usid
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id) left join
					tipo_hca th ON (hstd.tipo_hca_id = th.id)
				Where
					hs.hca_id = " . Utils::phpIntToSQL($idHCA) ." AND
					th.id = 2 AND
					hs.estado='ACTIVO'
				order by fecha_creacion DESC limit 1
				;";
		
		$this->firephp->log($query);
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		switch (Utils::sqlIntToPHP($result['destino'])) {
			case 1:
				$ret->destino = TipoDestinoHS::ALTA;
				break;
			
			case 2:
				$ret->destino = TipoDestinoHS::DERIVACION;
				break;	
			
			case 3:
				$ret->destino = TipoDestinoHS::DERIVACIONint;
				$ret->centro = Utils::sqlIntToPHP($result['centro']);
				break;

			case 4:
				$ret->destino = TipoDestinoHS::INTERNACION;
				$ret->servicio = Utils::sqlIntToPHP($result['servicio']);
				break;
			
			case 5:
				$ret->destino = TipoDestinoHS::OBITO;
				break;
                            
                        case 13:
				$ret->destino = TipoDestinoHS::RETIROVOLUNTARIO;
				break;
				
			default:
				throw new Exception("Out of range exception",2412);
		}
		
		$ret->diagnostico = Utils::sqlIntToPHP($result['diagno']);
		$ret->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha_creacion']);
		$ret->profesional = Utils::sqlIntToPHP($result['profesional']);
		$ret->sectorFisico = Utils::sqlIntToPHP($result['sector']);
		$ret->id = Utils::sqlIntToPHP($result['id']);
		$ret->usr = $result['usid'];
		
		
		return $ret;
	}
	
	
	
	/**
	 * 
	 * Retorna una salida con sus datos basicos
	 * @param int $idHCA
	 * @return Salida
	 */
	function salida($idHCA)
	{
		$ret = new Salida();
		
		$query = "select 
					hs.fecha_creacion,  
					hs.cod_diagno as diagno,
					hs.cod_profes as profesional,
					hs.id,
					hs.usid
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id)
				Where
					hs.hca_id = " . Utils::phpIntToSQL($idHCA) ."
					AND hs.estado = 'ACTIVO'
				order by fecha_creacion DESC limit 1
				;";
		
		$this->firephp->log($query);
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		
		
		$ret->diagnostico = Utils::sqlIntToPHP($result['diagno']);
		$ret->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha_creacion']);
		$ret->profesional = Utils::sqlIntToPHP($result['profesional']);
		$ret->id = Utils::sqlIntToPHP($result['id']);
		$ret->usr = $result['usid'];
		
		
		return $ret;
	}
	
	
/**
	 * 
	 * Retorna la salida UDP de la internacion, es requisito que exista
	 * @param int $idHCA
	 * @return SalidaHS
	 */
	function salidaHSUDP($idHCA)
	{
		$ret = new SalidaUDP();
		
		$query = "select 
					hs.fecha_creacion,  
					hs.cod_diagno as diagno,
					hs.cod_profes as profesional,
					hs.tipo_destino_id as destino,
					hs.cod_centro as centro,
					hs.cod_servicio as servicio,
					hs.cod_secfis as sector,
					hs.id,
					hs.usid
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id) left join
					tipo_hca th ON (hstd.tipo_hca_id = th.id)
				Where
					hs.hca_id = " . Utils::phpIntToSQL($idHCA) . " AND
					th.id = 1 AND 
					hs.estado = 'ACTIVO'
				order by fecha_creacion DESC limit 1
				;";
		
		//$this->firephp->log($query,"Esta mal?");
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		switch (Utils::sqlIntToPHP($result['destino'])) {
			case 6:
				$ret->destino = TipoDestinoUDP::ALTA;
				break;
			
			case 7:
				$ret->destino = TipoDestinoUDP::DERIVACION;
				break;	
			
			case 8:
				$ret->destino = TipoDestinoUDP::DERIVACIONint;
				$ret->centro = Utils::sqlIntToPHP($result['centro']);
				break;

			case 9:
				$ret->destino = TipoDestinoUDP::INTERNACION;
				$ret->servicio = Utils::sqlIntToPHP($result['servicio']);
				break;
			
			case 10:
				$ret->destino = TipoDestinoUDP::TERAPIA;
				break;
				
			case 11:
				$ret->destino = TipoDestinoUDP::QUIROFANO;
				break;
					
			case 12:
				$ret->destino = TipoDestinoUDP::OBITO;
				break;
                            
                        case 14:
				$ret->destino = TipoDestinoUDP::RETIROVOLUNTARIO;
				break;
				
			default:
				throw new Exception("Out of range exception",2412);
			break;
		}
		
		$ret->diagnostico = Utils::sqlIntToPHP($result['diagno']);
		$ret->fecha = Utils::sqlDateTimeToPHPTimestamp($result['fecha_creacion']);
		$ret->profesional = Utils::sqlIntToPHP($result['profesional']);
		$ret->sectorFisico = Utils::sqlIntToPHP($result['sector']);
		$ret->id = Utils::sqlIntToPHP($result['id']);
		$ret->usr = $result['usid'];
		
		return $ret;
	}
	
	/**
	 * 
	 * indica si el paciente ya egreso de la UDP
	 * @param int $idHca
	 * @return bool
	 */
	/*
	function hayEgresoUDP($idHca)
	{
		$query = "select 
					fecha_egreso 
				from 
					hca 
				where 
					id = " .Utils::phpIntToSQL($idHca).";";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		
		$egreso = Utils::sqlDateTimeToPHPTimestamp($result['fecha_egreso']);
		
		return ($egreso!=NULL);
	}*/
	
	/**
	 * 
	 * indica si el paciente ya egreso de la UDP
	 * @param int $idHca
	 * @return bool
	 */
	function hayEgresoHSUDP($idHca)
	{
		$query = "select 
					count(*) as cantidad
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id) left join
					tipo_hca th ON (hstd.tipo_hca_id = th.id)
				where 
					hca_id = " .Utils::phpIntToSQL($idHca)." AND
					th.id = 1
					AND hs.estado = 'ACTIVO'
					;";
		
		//$this->firephp->log($query,"Funciona esta consulta");
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		
		$egreso = Utils::sqlIntToPHP($result['cantidad']);
		
		return ($egreso>0);
	}
	
	/**
	 * 
	 * indica si el paciente ya egreso de la UDP
	 * @param int $idHca
	 * @return bool
	 */
	function hayEgresoHSDAP($idHca)
	{
		$query = "select 
					count(*) as cantidad
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id) left join
					tipo_hca th ON (hstd.tipo_hca_id = th.id) 
				where 
					hca_id = " .Utils::phpIntToSQL($idHca)." AND
					th.id = 2
					AND hs.estado = 'ACTIVO'
					;";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		
		$egreso = Utils::sqlIntToPHP($result['cantidad']);
		
		return ($egreso>0);
	}
	
	
/**
	 * 
	 * indica si el paciente ya egreso de HCA
	 * @param int $idHca
	 * @return bool
	 */
	function hayEgreso($idHca)
	{
		$query = "select 
					count(*) as cantidad
				from 
					hca_salida hs left join
					hca_salida_tipo_destino hstd ON (hs.tipo_destino_id = hstd.id) left join
					tipo_hca th ON (hstd.tipo_hca_id = th.id) 
				where 
					hca_id = " .Utils::phpIntToSQL($idHca)."
					AND hs.estado = 'ACTIVO' 
					;";
		
		try
		{
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("No se pudo ejecutar la consulta" . $e->getMessage(), 201230);
		}
		
		$result = $this->baseDeDatos->fetchRow();
		
		$egreso = Utils::sqlIntToPHP($result['cantidad']);
		
		return ($egreso>0);
	}
	
	function insertarLaboratorio($idHca, Labortatorio $laboratorio, $usid)
	{
		$query ="insert into 
					hca_laboratorios 
					(
					hca_lista_laboratorio_id,
					hca_id,";
		
		if($laboratorio->esNumerico)
		{
			$query .= " value ,";
		}
		else 
		{
			$query .= " text_value ,";
		}
			
		$query .="	usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpIntToSQL($laboratorio->id).",
					".Utils::phpIntToSQL($idHca).", ";
		if($laboratorio->esNumerico)
		{
			$query .= Utils::phpFloatToSQL($laboratorio->valor) . " ,";
		}
		else 
		{
			$query .= Utils::phpStringToSQL($laboratorio->valor) . " ,";
		}
					
		$query .=	Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}

	/**
	 * 
	 * Agrega un estudio de Rx a la base de datos
	 * @param int $idHca
	 * @param Rayo $estudio
	 * @param string $usid: Usuario
	 */
	function insertarRx($idHca, Rayo $estudio, $usid)
	{
		$query ="insert into 
					hca_rx 
					(
					hca_lista_rx_id,
					hca_id,
					value,
					observacion,
					usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpIntToSQL($estudio->id).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpBoolToSQL($estudio->valor).",
					".Utils::phpStringToSQL($estudio->observacion).",
					".Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}

	/**
	 * 
	 * Agrega un estudio de Alta Complejidad a la base de datos
	 * @param int $idHca
	 * @param AltaComplejidad $estudio
	 * @param string $usid: Usuario
	 */
	function insertarAltaComplejidad($idHca, AltaComplejidad $estudio, $usid)
	{
		$query ="insert into 
					hca_alta_complejidad 
					(
					hca_lista_alta_complejidad_id,
					hca_id,
					value,
					usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpIntToSQL($estudio->id).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpBoolToSQL($estudio->valor).",
					".Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	function insertarObservacion($idHca, Observacion $obs, $usid)
	{
		$query ="insert into 
					hca_observaciones 
					(
					observacion,
					hca_tipo_observacion,
					hca_id,
					usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpStringToSQL($obs->descripcion).",
					".Utils::phpIntToSQL(TipoObservacion::OBSERVACION).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	function insertarPendiente($idHca, Pendiente $pend, $usid)
	{
		$query ="insert into 
					hca_observaciones 
					(
					observacion,
					hca_tipo_observacion,
					hca_id,
					usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpStringToSQL($pend->descripcion).",
					".Utils::phpIntToSQL(TipoObservacion::PENDIENTE).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	function insertarInterconsulta($idHca, Interconsulta $inter, $usid)
	{
		$query ="insert into 
					hca_observaciones 
					(
					observacion,
					hca_tipo_observacion,
					hca_id,
					usid,
					fecha_creacion
					)
					values
					(
					".Utils::phpStringToSQL($inter->descripcion).",
					".Utils::phpIntToSQL(TipoObservacion::INTERCONSULTA).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpStringToSQL($usid).",
					now()
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	
	function crearHCA($tipodoc, $nrodoc,$fechaIngreso, $motivo,$secfis,$obrasocial,$diagnostico,$interv_policial,  $usid, $tipoHCA)
	{
		$query ="insert into 
					hca 
					(
					tipodoc,
					nrodoc,
					motivo_consulta,
					fecha_ingreso,
					fecha_creacion,
					tipo_hca_id,
					cod_diagno_ingreso,
					cod_secfis,
					cod_osoc,
					interv_policial,
					usid
					)
					values
					(
					".Utils::phpIntToSQL($tipodoc).",
					".Utils::phpIntToSQL($nrodoc).",
					".Utils::phpStringToSQL($motivo).",
					".Utils::phpTimestampToSQLDatetime($fechaIngreso).",
					now(),
					".Utils::phpIntToSQL($tipoHCA).",
					".Utils::phpIntToSQL($diagnostico).",
					".Utils::phpIntToSQL($secfis).",
					".Utils::phpIntToSQL($obrasocial).",
					".Utils::phpBoolToSQL($interv_policial).",
					".Utils::phpStringToSQL($usid)."
					);
			";
		
		try {
			
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
			$ret = $this->baseDeDatos->lastInsertId();
			
		} catch (Exception $e) {
			
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
		return $ret;
	}
	
	/**
	 * 
	 * Le asigna al HCA el id de formulario UGCAM
	 * @param int $hcaId
	 * @param int $ugcamId
	 * @return bool
	 */
	function asociarUGCAM($hcaId, $ugcamId)
	{
		$ret = false;
		
		$query ="UPDATE 
					hca
				SET
					ugcam_form_id = ".Utils::phpIntToSQL($ugcamId)."
				WHERE
					id = ".Utils::phpIntToSQL($hcaId).";";
		
		try {
			
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
			$ret = true;
			
		} catch (Exception $e) {
			
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
		return $ret;
	}
	
	
	/*
	function insertarSalidaUDP($idHca, SalidaUDP $salida, $usid)
	{
		$query ="update
					hca
					set
					fecha_egreso = ".Utils::phpTimestampToSQLDatetime($salida->fecha).",
					cod_diagno_egreso = ".Utils::phpIntToSQL($salida->diagnostico).",
					cod_profes_egreso = ".Utils::phpIntToSQL($salida->profesional).",
					usid = ".Utils::phpStringToSQL($usid).",
					tipo_destino = ".Utils::phpIntToSQL($salida->destino).",
					fecha_creacion = now()
				where
				 id = ".Utils::phpIntToSQL($idHca).";
			";
		
		//$this->firephp->log($query,"Consulta insert");
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	*/
	
	function ultimaHoraSalida($idHCA)
	{
		$query = "Select 
					fecha_creacion
				from 
					hca_salida
				where
					hca_id = ".Utils::phpIntToSQL($idHCA)."
				order by fecha_creacion DESC
				limit 1
				;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$result =  $this->baseDeDatos->fetchRow();
		$ret = Utils::sqlDateTimeToPHPTimestamp($result['fecha_creacion']);
		
		$this->baseDeDatos->desconectar();
		
		return $ret;
	}
	
	function ultimoSectorFisico($idHCA)
	{
		$query = "Select 
					cod_secfis
				from 
					hca_salida
				where
					hca_id = ".Utils::phpIntToSQL($idHCA)."
				order by id DESC
				limit 1
				;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$result =  $this->baseDeDatos->fetchRow();
		$ret = Utils::sqlIntToPHP($result['cod_secfis']);
		
		$this->baseDeDatos->desconectar();
		
		return $ret;
	}
	
	function tuvoSalida($idHCA)
	{
		$query = "Select 
					id
				from 
					hca_salida
				where
					hca_id = ".Utils::phpIntToSQL($idHCA)."
				;";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->firephp->log($query,"Consulta tubo salida");
		
		if($this->baseDeDatos->querySize()>0)
		{
			$ret = true;
		}
		else 
		{
			$ret = false;
		}
		
		$this->baseDeDatos->desconectar();
		
		return $ret;
				
	}
	
	function insertarSalidaUDP($idHca, SalidaUDP $salida, $usid)
	{
		$query ="insert into 
					hca_salida
					(
					cod_diagno,
					cod_profes,
					cod_centro,
					cod_servicio,
					usid,
					fecha_creacion,
					tipo_destino_id,
					hca_id,
					cod_secfis
					)
					values
					(
					".Utils::phpIntToSQL($salida->diagnostico).",
					".Utils::phpIntToSQL($salida->profesional).",
					".Utils::phpIntToSQL($salida->centro).",
					".Utils::phpIntToSQL($salida->servicio).",
					".Utils::phpStringToSQL($usid).",
					".Utils::phpTimestampToSQLDatetime($salida->fecha).",
					".Utils::phpIntToSQL($salida->destino).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpIntToSQL($salida->sectorFisico)."
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	function insertarSalidaHS($idHca, SalidaHS $salida, $usid)
	{
		$query ="insert into 
					hca_salida
					(
					cod_diagno,
					cod_profes,
					cod_centro,
					cod_servicio,
					usid,
					fecha_creacion,
					tipo_destino_id,
					hca_id,
					cod_secfis
					)
					values
					(
					".Utils::phpIntToSQL($salida->diagnostico).",
					".Utils::phpIntToSQL($salida->profesional).",
					".Utils::phpIntToSQL($salida->centro).",
					".Utils::phpIntToSQL($salida->servicio).",
					".Utils::phpStringToSQL($usid).",
					now(),
					".Utils::phpIntToSQL($salida->destino).",
					".Utils::phpIntToSQL($idHca).",
					".Utils::phpIntToSQL($salida->sectorFisico)."
					);
			";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error intentando ejecutar: $query");
		}
		
		$this->baseDeDatos->desconectar();
	}
	
	/**
	 * 
	 * Devuelve un JSON con los datos de los pacientes en UDP sin salida
	 * @return JSON $resumenInternaciones
	 */
	function pacientesInternadosEnUDP($page, $limit, $sidx,$sord) 
	{
		//TODO: si llego a tener limit lo pasaria por aca
		$count = $this->cantidadPacientesInternadosEnUDP($page, $limit, $sidx, $sord);
		$query = $this->queryPacientesInternadosEnUDP($count, $page, $limit,$sidx,$sord);

		//$this->firephp->log($query, "Query Pacientes internados");
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
		}
		
		
		//CONTEO DE LAS PAGINAS Y TOTALES
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		//FIN CONTEO DE LAS PAGINAS Y TOTALES	 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
		 
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result["id"]);			
			$row = array();
			
			$row[] = Utils::sqlIntToPHP($result["cama"]);
			$row[] = Utils::sqlIntToPHP($result["nrodoc"]);
			
			$row[] = htmlentities($result["nombre"]);
			//$row[] = ($result["ingreso"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"]));
			
			$row[] = htmlentities($result["motivo"]);
			
			
			$row[] = '';  //Para el boton seleccionar
			$response->rows[$i]['cell'] = $row;

			
		}
		
		$this->baseDeDatos->desconectar();
		
		return json_encode($response);
	}
	
	/**
	 * 
	 * Devuelve un JSON con los datos de los pacientes en DAP sin salida
	 * @return JSON $resumenInternaciones
	 */
	function pacientesInternadosEnDAP($page, $limit, $sidx,$sord) 
	{
		//TODO: si llego a tener limit lo pasaria por aca
		$count = $this->cantidadPacientesInternadosEnDAP($page, $limit, $sidx, $sord);
		$query = $this->queryPacientesInternadosEnDAP($count, $page, $limit, $sidx, $sord);

		//$this->firephp->log($query, "Query Pacientes internados");
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		
		//CONTEO DE LAS PAGINAS Y TOTALES
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		//FIN CONTEO DE LAS PAGINAS Y TOTALES	 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
		 
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result["id"]);			
			$row = array();
			
			$row[] = Utils::sqlIntToPHP($result["cama"]);
			$row[] = Utils::sqlIntToPHP($result["nrodoc"]);
			
			$row[] = htmlentities($result["nombre"]);
			//$row[] = ($result["ingreso"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"]));
			
			$row[] = htmlentities($result["motivo"]);
			
			
			$row[] = '';  //Para el boton seleccionar
			$response->rows[$i]['cell'] = $row;

			
		}
		
		$this->baseDeDatos->desconectar();
		
		return json_encode($response);
	}
	
	
	/**
	 * 
	 * Devuelve un JSON con los datos de los pacientes de UDP del mes especificado
	 * @return JSON $pacientesUDP
	 */
	function pacientesDAPxMes($page, $limit, $sidx,$sord,$ano,$mes,$where=null) 
	{
		//TODO: si llego a tener limit lo pasaria por aca
		$count = $this->cantidadPacientesDAP($ano, $mes,$where);
		$query = $this->queryPacientesDAP($count, $page, $limit, $sidx, $sord, $ano, $mes,$where);

		//$this->firephp->log($query, "Query Pacientes internados");
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		
		//CONTEO DE LAS PAGINAS Y TOTALES
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		//FIN CONTEO DE LAS PAGINAS Y TOTALES	 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
		 
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result["id"]);			
			$row = array();
			
			$row[] = Utils::sqlIntToPHP($result["tipodoc"]);
			$row[] = Utils::sqlIntToPHP($result["nrodoc"]);
			$row[] = ($result["nombre"]);
			//$row[] = ($result["ingreso"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"]));
			
			
			$row[] = htmlentities($result["motivo"]);
			
			$row[] = htmlentities($result["destinoDAP"]);
			$row[] = htmlentities($result["diagEgreso"]);
			$row[] = htmlentities($result["profesional"]);
			$row[] = htmlentities($result["obraSocial"]);
			if(Utils::sqlBoolToPHP($result["ip"]))
			{
				$ip = "Si";
			}
			else 
			{
				$ip = "No";
			}
			$row[] = htmlentities($ip);
			
			
			$row[] = '';  //Para el boton seleccionar
			$response->rows[$i]['cell'] = $row;

			
		}
		
		$this->baseDeDatos->desconectar();
		
		return json_encode($response);
		
	}
	
	/**
	 * 
	 * Devuelve un JSON con los datos de los pacientes de UDP del mes especificado
	 * @return JSON $pacientesUDP
	 */
	function pacientesUDPxMes($page, $limit, $sidx,$sord,$ano,$mes,$where=null) 
	{
		//TODO: si llego a tener limit lo pasaria por aca
		$count = $this->cantidadPacientesUDP($ano, $mes,$where);
		$query = $this->queryPacientesUDP($count, $page, $limit, $sidx, $sord, $ano, $mes,$where);

		//$this->firephp->log($query, "Query Pacientes UDP");
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		
		//CONTEO DE LAS PAGINAS Y TOTALES
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		//FIN CONTEO DE LAS PAGINAS Y TOTALES	 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
		 
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result["id"]);			
			$row = array();
			
			$row[] = Utils::sqlIntToPHP($result["tipodoc"]);
			$row[] = Utils::sqlIntToPHP($result["nrodoc"]);
			$row[] = Utils::phpStringToHTML($result["nombre"]);
//			$row[] = ($result["ingreso"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"]));
			
			
			$row[] = Utils::phpStringToHTML($result["motivo"]);
			
			
			$sector = Utils::sqlIntToPHP($result["cod_secfis"]);
			
			switch ($sector) {
				case 234:
					$texto = "UDP Cirugia";
					break;
					
				case 207:
					$texto = "UDP Clinica";
					break;
					
				case 199:
					$texto = "UDP Traumatologia";
					break;
				
				case 228:
					$texto = "UDP Pediatria";
					break;
					
				case 236:
					$texto = "UDP ORL Materno";
					break;
					
				case 237:
					$texto = "UDP UROLOGIA";
					break;

				case 238:
					$texto = "UDP Pediatria Clínica";
					break;

				case 239:
					$texto = "UDP Cardiología Agudos";
					break;
					
				case 240:
					$texto = "UDP Cardiología";
					break;

				case 249:
					$texto = "UDP Dermatologia";
					break;
				
				case 105:
					$texto = "UDP Shockroom";
					break;

				case 255:
					$texto = "UDP ORL";
					break;

				default:
					$this->firephp->log($sector,"SECTOR");
					$texto = "N/A";
				break;
			}
			
			$row[] = Utils::phpStringToHTML($texto);
			
			
			$row[] = htmlentities($result["destinoUDP"]);
			$row[] = Utils::phpStringToHTML($result["diagEgreso"]);
			$row[] = Utils::phpStringToHTML($result["profesional"]);
			
			$row[] = Utils::phpStringToHTML($result["obraSocial"]);
			if(Utils::sqlBoolToPHP($result["ip"]))
			{
				$ip = "Si";
			}
			else 
			{
				$ip = "No";
			}
			$row[] = htmlentities($ip);
            $row[] =Utils::phpStringToHTML($result["salidaIEH"]);
			
			$row[] = '';  //Para el boton seleccionar
			$response->rows[$i]['cell'] = $row;

			
		}
		
		$this->baseDeDatos->desconectar();
		
		return json_encode($response);
		
	}
	
	function queryPacientesInternadosEnDAP($count, $page, $limit,$sidx,$sord)
	{
		if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
		else 
			{ 
				$total_pages = 0; 
			}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
			 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) {
			$start = 0;
		}
		
		$query = "select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					hca.motivo_consulta as motivo,
					hca.fecha_ingreso as ingreso,
					fp.nombre as nombre, 
					c.nombre as centro,
					sf.des_secfis as secfis,
					ca.cama as cama
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					incli inc on (hca.tipodoc = inc.tipodoc and hca.nrodoc = inc.nrodoc) left join
					insecfis sf on (inc.cod_secfis = sf.cod_secfis) left join
					centros c on (c.cod_centro = inc.cod_centro) left join
					camas ca on (inc.cod_cama = ca.cod_cama) left join
					hca_salida hs on (hca.id = hs.hca_id and hs.estado='ACTIVO')
				where 
					hs.id is null 
					and hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::CAP). "";
		$query = $query . " ORDER BY $sidx $sord LIMIT $start , $limit;";
				
		
		return $query;
	}
	
	function queryPacientesInternadosEnUDP($count, $page, $limit,$sidx,$sord)
	{
		if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
		else 
			{ 
				$total_pages = 0; 
			}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
			 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) {
			$start = 0;
		}
		
		$query = "select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					hca.motivo_consulta as motivo,
					hca.fecha_ingreso as ingreso,
					fp.nombre as nombre, 
					c.nombre as centro,
					sf.des_secfis as secfis,
					ca.cama as cama
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					incli inc on (hca.tipodoc = inc.tipodoc and hca.nrodoc = inc.nrodoc) left join
					insecfis sf on (inc.cod_secfis = sf.cod_secfis) left join
					centros c on (c.cod_centro = inc.cod_centro) left join
					camas ca on (inc.cod_cama = ca.cod_cama) left join
					hca_salida hs on (hca.id = hs.hca_id and hs.estado='ACTIVO')
				where 
					hs.id is null 
					and hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::UDP). "";
		$query = $query . " ORDER BY $sidx $sord LIMIT $start , $limit;";
				
		
		return $query;
	}
	
	function queryPacientesUDP($count, $page, $limit,$sidx,$sord,$ano,$mes,$where=null)
	{
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} 
		else 
		{ 
			$total_pages = 0; 
		}
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) 
		{
			$start = 0;
		}
		$query = "select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					hca.fecha_ingreso as ingreso,
					hs.fecha_creacion as egreso,
					hca.motivo_consulta as motivo,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta UDP') as destinoUDP,
					os.cod_busq as obraSocial,
					if(hstd.nombre is NULL,ifnull(sfi.cod_secfis, sfs.cod_secfis),sfs.cod_secfis) as cod_secfis, 
					timediff(hs.fecha_creacion,hca.fecha_ingreso) as tiempo,
					hca.interv_policial as ip,
					if(shim.cod_diagno is NULL,'#N/A',min(dl2.diag_local)) as salidaIEH
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id)left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (hca.cod_osoc = os.cod_osoc) left join
					diaglocal dl on (dl.id_diagno = hs.cod_diagno) left join
					incli ic on (ic.tipodoc = hca.tipodoc and ic.nrodoc = hca.nrodoc and ic.fecha_ingreso = hca.fecha_ingreso) left join
					insecfis sfs on (hs.cod_secfis = sfs.cod_secfis) left join
					insecfis sfi on (ic.cod_secfis = sfi.cod_secfis) left join
					himef shim on (hca.tipodoc = shim.tipodoc  and hca.nrodoc = shim.nrodoc  and  hca.fecha_ingreso  = shim.fecha_ingreso and shim.motivo=1 ) left join
					diaglocal dl2 on (shim.cod_diagno = dl2.cod_diagno )
				where 
					year(hca.fecha_ingreso) = ".Utils::phpIntToSQL($ano)." and 
					month(hca.fecha_ingreso)=".Utils::phpIntToSQL($mes)." and
					hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::UDP). " ";
		if($where!=null)
		{
			$query = $query . $where;
		}
		        
		$query = $query . " GROUP BY hca.tipodoc, hca.nrodoc, hca.fecha_ingreso ";

		$_SESSION['pacientesUDP'] = $query . " ORDER BY $sidx $sord;";
		$this->firephp->log($_SESSION['pacientesUDP'], "Pacientes");
		$query = $query . " ORDER BY $sidx $sord LIMIT $start , $limit;";
		//$this->firephp->log($query,"Consulta UDP");
		return $query;
	}
	
	function queryPacientesDAP($count, $page, $limit,$sidx,$sord,$ano,$mes,$where=null)
	{
		if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
		else 
			{ 
				$total_pages = 0; 
			}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
			 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) {
			$start = 0;
		}
		
		$query = "select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					hca.fecha_ingreso as ingreso,
					hca.motivo_consulta as motivo,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta DAP') as destinoDAP,
					os.cod_busq as obraSocial,
					hca.interv_policial as ip
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id)left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (hca.cod_osoc = os.cod_osoc) left join
					diaglocal dl on (dl.id_diagno = hs.cod_diagno)
				where 
					year(hca.fecha_ingreso) = ".Utils::phpIntToSQL($ano)." and 
					month(hca.fecha_ingreso)=".Utils::phpIntToSQL($mes)." and
					hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::CAP). " ";
		if($where!=null)
		{
			$query = $query . $where;
		}
		
		$_SESSION['pacientesDAP'] = $query . " ORDER BY $sidx $sord;";
		
		$query = $query . " ORDER BY $sidx $sord LIMIT $start , $limit;";
				
		
		return $query;
	}
	
	function jsonUDPsPaciente($tipoDoc, $nroDoc)
	{
		$query = "select 
					hca.id,
					hca.fecha_ingreso, 
					hca.motivo_consulta,
					th.nombre as tipoHCA,
					hs.fecha_creacion as fecha_egreso,
					if(hs.cod_diagno>100000,cie.dec10,dl.diag_local) as diagnostico,
					p.nombre as profesional
				from 
					hca left join
					hca_salida hs on (hca.id = hs.hca_id and hs.estado = 'ACTIVO') left join
					tipo_hca th on (hca.tipo_hca_id = th.id) left join
					diaglocal dl on (hs.cod_diagno = dl.id_diagno) left join
					cie10 cie on (hs.cod_diagno = cie.id) left join 
					profesionales p on (hs.cod_profes = p.cod_profes)
				Where
					hs.id is not null and 
					hca.tipodoc = ". Utils::phpIntToSQL($tipoDoc)." and
					hca.nrodoc = ".Utils::phpIntToSQL($nroDoc)."
					;";
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		
		
		$response->page = 1;
		$response->total = 1;
		$response->records=count($this->baseDeDatos->querySize());
		
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			
			$result = $this->baseDeDatos->fetchRow(); 
			
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result['id']);
					
			$row = array();
		
		
			$row[] = htmlentities($result['tipoHCA']);
			$row[] = $result['fecha_ingreso'];
			$row[] = htmlentities($result['motivo_consulta']);
			$row[] = htmlentities($result['diagnostico']);
			$row[] = htmlentities($result['profesional']);
					
		
			$response->rows[$i]['cell'] = $row; 
			
		}
		
		return json_encode($response);
	}
	
	/**
	 * 
	 * Lee todas las llamadas realizadas para el id de formulario pasado
	 * retorna un arreglo con los tres campos a mostrar en la tabla en cada fila
	 * @param int $idUGCAMForm
	 * @throws Exception
	 */
	function llamadasUGCAM($idUGCAMForm)
	{
		$query = "select 
					fecha_creacion as fecha,
					observaciones,
					entrevistador
				from 
					llamadas_ugcam
				Where
					ugcam_form_id = " . Utils::phpIntToSQL($idUGCAMForm) ."
				order by fecha_creacion DESC
					;";	
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		} catch (Exception $e) {
			throw new Exception("Error ejecutando query: " . $query ." " .$e->getMessage(),2054);
		}
		
		$ret= array();
		for ($i = 0; $i < $this->baseDeDatos->querySize; $i++) {
			$result = $this->baseDeDatos->fetchRow();
			
			$row['fecha'] = Utils::sqlDateTimeToPHPTimestamp($result['fecha']);
			$row['observaciones'] = $result['observaciones'];
			$row['entrevistador'] = $result['entrevistador'];
			
			$ret[]= $row;
		}
		
		$this->baseDeDatos->desconectar();
		
		return $ret;
	}
	
	/**
	 * 
	 * Inserta en la base la llamada con los datos pasados, 
	 * retorna el id de la llamada
	 * @param int $ugcamId
	 * @param string $observacion
	 * @param string $entrevistador
	 * @param string $userid
	 * @throws Exception
	 * @return int
	 */
	function guardarLlamadaUGCAM($ugcamId,$observacion,$entrevistador,$userid)
	{
		$query = "	INSERT INTO
						llamadas_ugcam
					(
						ugcam_form_id,
						observaciones,
						entrevistador,
						fecha_creacion,
						usid
					)
					values
					(
						".Utils::phpIntToSQL($ugcamId).",
						".Utils::phpStringToSQL($observacion).",
						".Utils::phpStringToSQL($entrevistador).",
						now(),
						".Utils::phpStringToSQL($userid)."
					);
		";
		
		try {
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarAccion($query);
		} catch (Exception $e) {
			throw new Exception("Error ejecutando query: " . $query ." " .$e->getMessage(),2054);
		}
		$ret = $this->baseDeDatos->lastInsertId();
		$this->baseDeDatos->desconectar();
		return $ret;
	}
	
	function cantidadPacientesUDP($ano,$mes,$where=null)
	{
		$query = "select count(*) as cantidad from (
					select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					hca.fecha_ingreso as ingreso,
					hca.motivo_consulta as motivo,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta UDP') as destinoUDP,
					os.cod_busq as obraSocial,
					ifnull(sfs.cod_secfis, sfi.cod_secfis) as cod_secfis
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id) left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (ieh.cod_osoc = os.cod_osoc)left join
					diaglocal dl on (dl.id_diagno = hs.cod_diagno) left join
					incli ic on (ic.tipodoc = hca.tipodoc and ic.nrodoc = hca.nrodoc and ic.fecha_ingreso = hca.fecha_ingreso) left join
					insecfis sfs on (hs.cod_secfis = sfs.cod_secfis) left join
					insecfis sfi on (ic.cod_secfis = sfi.cod_secfis) 
				where 
					year(hca.fecha_ingreso) = ".Utils::phpIntToSQL($ano)." and 
					month(hca.fecha_ingreso)=".Utils::phpIntToSQL($mes)." and 
					hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::UDP).	" ";
		
		if($where!=null)
		{
			$query = $query . $where;
		}
		
		$query = $query . ") consulta;";
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		
		return $cantidad;
	}
	
	function cantidadPacientesDAP($ano,$mes,$where=null)
	{
		$query = "select count(*) as cantidad from (
					select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					hca.fecha_ingreso as ingreso,
					hca.motivo_consulta as motivo,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta DAP') as destinoDAP,
					os.cod_busq as obraSocial
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id) left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (ieh.cod_osoc = os.cod_osoc) left join 
					diaglocal dl on (dl.id_diagno = hs.cod_diagno) 
				where 
					year(hca.fecha_ingreso) = ".Utils::phpIntToSQL($ano)." and 
					month(hca.fecha_ingreso)=".Utils::phpIntToSQL($mes)." and 
					hca.tipo_hca_id = " . Utils::phpIntToSQL(TipoHCA::CAP). " ";
		
		if($where!=null)
		{
			$query = $query . $where;
		}
		
		$query = $query . ") consulta;";
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		
		return $cantidad;
	}
	
	function cantidadPacientesInternadosEnUDP($page, $limit,$sidx,$sord)
	{
		$query = $query = "select 
					count(*) as cantidad
				from
					hca left join
					hca_salida on (hca.id = hca_salida.hca_id and hca_salida.estado='ACTIVO')
				where 
					hca_salida.id is null
					and hca.tipo_hca_id = ".Utils::phpIntToSQL(TipoHCA::UDP)."
				;";
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		
		return $cantidad;
	}
	
	
	
	function cantidadPacientesInternadosEnDAP($page, $limit,$sidx,$sord)
	{
		$query = "select 
					count(*) as cantidad
				from
					hca left join
					hca_salida on (hca.id = hca_salida.hca_id and hca_salida.estado='ACTIVO')
				where 
					hca_salida.id is null
					and hca.tipo_hca_id = ".Utils::phpIntToSQL(TipoHCA::CAP)."
				;";
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		
		return $cantidad;
	}
	
	function pacientesUDPToExcel() 
	{
		try
		{
			$this->baseDeDatos->conectar();
		}
		catch (Exception $e)
		{
			throw new Exception("Error conectando a la base de datos", 20123);
		}
		
		
		$query = $_SESSION['pacientesUDP'];
		
		
		
		
		try
		{
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("Error ejecutando query:" . $query, 20123);
		}
		$hora = time();
		$filename = $hora . "PacientesUDP.xls";
		
		$docExcel = new Spreadsheet_Excel_Writer(); 
		
		$nuevahoja =& $docExcel->addWorksheet("Pacientes UDP");
		
		$format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
									  'Color' => 'black',
									  'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
		
		//echo "Cant Resultados: " . $this->baseDeDatos->querySize();
		$fila =0;
		//escribo los titulos despues veo hacerlos negrita
		$nuevahoja->write($fila, 0,"tipoDoc",$format);
		$nuevahoja->write($fila, 1,"NroDoc",$format);
		$nuevahoja->write($fila, 2,"Nombre",$format);
		$nuevahoja->write($fila, 3,"Ingreso",$format);
		$nuevahoja->write($fila, 4,"Motivo",$format);
		$nuevahoja->write($fila, 5,"Sector",$format);
		$nuevahoja->write($fila, 6,"destino UDP",$format);
		$nuevahoja->write($fila, 7,"diagnostico",$format);
		$nuevahoja->write($fila, 8,"Profesional",$format);
		$nuevahoja->write($fila, 9,"Obra Social",$format);
        $nuevahoja->write($fila, 10,"Diag. Salida IEH",$format);
		
		
		//$nuevahoja->write($fila, 10,"Egreso",$format);
		//$nuevahoja->write($fila, 11,"Tiempo",$format);
		
		$fila=1;
		
	for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			//$response->rows[$i]['id'] = "pp$i";  //Le pongo el $i porque no lo voy a usar
			$columna=0;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["tipodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::sqlIntToPHP($result["nrodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["nombre"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"])));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["motivo"]));
			$columna++; 
			
			$sector = Utils::sqlIntToPHP($result["cod_secfis"]);
			
			switch ($sector) {
				case 234:
					$texto = "UDP Cirugia";
					break;
					
				case 207:
					$texto = "UDP Clinica";
					break;
					
				case 199:
					$texto = "UDP Traumatologia";
					break;
				
				case 228:
					$texto = "UDP Pediatria";
					break;
					
				case 236:
					$texto = "UDP ORL Materno";
					break;
					
				case 237:
					$texto = "UDP UROLOGIA";
					break;

				case 238:
					$texto = "UDP Pediatria Clínica";
					break;

				case 239:
					$texto = "UDP Cardiología Agudos";
					break;

				case 240:
					$texto = "UDP Cardiología";
					break;

				case 249:
					$texto = "UDP Dermatologia";
					break;
				
				case 105:
					$texto = "UDP Shockroom";
					break;

				case 255:
					$texto = "UDP ORL";
					break;

				default:
					$texto = "N/A";
				break;
			}
			
			$nuevahoja->write($fila, $columna,utf8_encode($texto));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["destinoUDP"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna, utf8_encode($result["diagEgreso"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["profesional"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["obraSocial"]));
			$columna++;

            $nuevahoja->write($fila, $columna,utf8_encode($result["salidaIEH"]));
            $columna++;
			
			
			/*
			$nuevahoja->write($fila, $columna,utf8_encode($result["egreso"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["tiempo"]));
			$columna++;*/
	
			$fila++;
			//$fecha_ingreso = strftime("%Y-%m-%d", Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));
			//$hora_ingreso = Utils::phpTimestampToHTMLTime(Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));		
			//$row[] = '<a target="_blank" href="../modiIEH.php3?COD_CENTRO=' . $centro . '&TIPO_DOC=' . Utils::sqlIntToPHP($result["cod_doc"]) . '&NRO_DOC=' . Utils::sqlIntToPHP($result["nro_doc"]) . '&dia_ingreso=' . $fecha_ingreso . '&hora_ingreso=' . $hora_ingreso . '">Detalle</a>';  //Para el boton de detalle
			
		}
		$this->baseDeDatos->desconectar();
		
		$docExcel->send($filename);
		$docExcel->close();
	}
	
	
	function pacientesUDPNacerToExcel() 
	{
		try
		{
			$this->baseDeDatos->conectar();
		}
		catch (Exception $e)
		{
			throw new Exception("Error conectando a la base de datos", 20123);
		}
		
		
		$query = $_SESSION['pacientesUDPNacer'];
		
		
		
		
		try
		{
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("Error ejecutando query:" . $query, 20123);
		}
		$hora = time();
		$filename = $hora . "PacientesUDPNacer.xls";
		
		$docExcel = new Spreadsheet_Excel_Writer(); 
		
		$nuevahoja =& $docExcel->addWorksheet("Pacientes UDP");
		
		$format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
									  'Color' => 'black',
									  'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
		
		//echo "Cant Resultados: " . $this->baseDeDatos->querySize();
		$fila =0;
		//escribo los titulos despues veo hacerlos negrita
		$nuevahoja->write($fila, 0,"tipoDoc",$format);
		$nuevahoja->write($fila, 1,"NroDoc",$format);
		$nuevahoja->write($fila, 2,"Nombre",$format);
		$nuevahoja->write($fila, 3,"Localidad",$format);
		$nuevahoja->write($fila, 4,"Direccion",$format);
		$nuevahoja->write($fila, 5,"Nacimiento",$format);
		$nuevahoja->write($fila, 6,"Edad",$format);
		$nuevahoja->write($fila, 7,"Sexo",$format);
		$nuevahoja->write($fila, 8,"Ingreso",$format);
		$nuevahoja->write($fila, 9,"Sector",$format);
		$nuevahoja->write($fila, 10,"destino UDP",$format);
		$nuevahoja->write($fila, 11,"diagnostico",$format);
		$nuevahoja->write($fila, 12,"Profesional",$format);
		$nuevahoja->write($fila, 13,"Obra Social",$format);
		
		
		//$nuevahoja->write($fila, 10,"Egreso",$format);
		//$nuevahoja->write($fila, 11,"Tiempo",$format);
		
		$fila=1;
		
	for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			//$response->rows[$i]['id'] = "pp$i";  //Le pongo el $i porque no lo voy a usar
			$columna=0;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["tipodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::sqlIntToPHP($result["nrodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["nombre"]));
			$columna++; 
			
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["localidad"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["direccion"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::phpTimestampToHTMLDate(Utils::sqlDateToPHPTimestamp($result["fechaNacimiento"])));
			$columna++;
			
			$nuevahoja->write($fila, $columna,Utils::sqlIntToPHP($result["edad"]));
			$columna++;

			$nuevahoja->write($fila, $columna,utf8_encode($result["sexo"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"])));
			$columna++; 
			
			$sector = Utils::sqlIntToPHP($result["cod_secfis"]);
			
			switch ($sector) {
				case 234:
					$texto = "UDP Cirugia";
					break;
					
				case 207:
					$texto = "UDP Clinica";
					break;
					
				case 199:
					$texto = "UDP Traumatologia";
					break;
				
				case 228:
					$texto = "UDP Pediatria";
					break;
					
				case 236:
					$texto = "UDP ORL Materno";
					break;
					
				case 237:
					$texto = "UDP UROLOGIA";
					break;

				case 238:
					$texto = "UDP Pediatria Clínica";
					break;

				case 239:
					$texto = "UDP Cardiología Agudos";
					break;

				case 240:
					$texto = "UDP Cardiología";
					break;
				
				case 249:
					$texto = "UDP Dermatologia";
					break;

				case 105:
					$texto = "UDP Shockroom";
					break;

				case 255:
					$texto = "UDP ORL";
					break;

				default:
					$texto = "N/A";
				break;
			}
			
			$nuevahoja->write($fila, $columna,utf8_encode($texto));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["destinoUDP"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna, utf8_encode($result["diagEgreso"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["profesional"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["obraSocial"]));
			$columna++;
			
			
			/*
			$nuevahoja->write($fila, $columna,utf8_encode($result["egreso"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["tiempo"]));
			$columna++;*/
	
			$fila++;
			//$fecha_ingreso = strftime("%Y-%m-%d", Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));
			//$hora_ingreso = Utils::phpTimestampToHTMLTime(Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));		
			//$row[] = '<a target="_blank" href="../modiIEH.php3?COD_CENTRO=' . $centro . '&TIPO_DOC=' . Utils::sqlIntToPHP($result["cod_doc"]) . '&NRO_DOC=' . Utils::sqlIntToPHP($result["nro_doc"]) . '&dia_ingreso=' . $fecha_ingreso . '&hora_ingreso=' . $hora_ingreso . '">Detalle</a>';  //Para el boton de detalle
			
		}
		$this->baseDeDatos->desconectar();
		
		$docExcel->send($filename);
		$docExcel->close();
	}
	
	function pacientesDAPToExcel() 
	{
		try
		{
			$this->baseDeDatos->conectar();
		}
		catch (Exception $e)
		{
			throw new Exception("Error conectando a la base de datos", 20123);
		}
		
		
		$query = $_SESSION['pacientesDAP'];
		
		
		
		
		try
		{
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw new Exception("Error ejecutando query:" . $query, 20123);
		}
		$hora = time();
		$filename = $hora . "PacientesDAP.xls";
		
		$docExcel = new Spreadsheet_Excel_Writer(); 
		
		$nuevahoja =& $docExcel->addWorksheet("Pacientes DAP");
		
		$format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
									  'Color' => 'black',
									  'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));
		
		//echo "Cant Resultados: " . $this->baseDeDatos->querySize();
		$fila =0;
		//escribo los titulos despues veo hacerlos negrita
		$nuevahoja->write($fila, 0,"tipoDoc",$format);
		$nuevahoja->write($fila, 1,"NroDoc",$format);
		$nuevahoja->write($fila, 2,"Nombre",$format);
		$nuevahoja->write($fila, 3,"Ingreso",$format);
		$nuevahoja->write($fila, 4,"Motivo",$format);
		$nuevahoja->write($fila, 5,"destino DAP",$format);
		$nuevahoja->write($fila, 6,"diagnostico",$format);
		$nuevahoja->write($fila, 7,"Profesional",$format);
		
		
		$fila=1;
		
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			//$response->rows[$i]['id'] = "pp$i";  //Le pongo el $i porque no lo voy a usar
			$columna=0;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["tipodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::sqlIntToPHP($result["nrodoc"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["nombre"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"])));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["motivo"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["destinoDAP"]));
			$columna++; 
			
			$nuevahoja->write($fila, $columna, utf8_encode($result["diagEgreso"]));
			$columna++;
			
			$nuevahoja->write($fila, $columna,utf8_encode($result["profesional"]));
			$columna++;
			
	
			$fila++;
			//$fecha_ingreso = strftime("%Y-%m-%d", Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));
			//$hora_ingreso = Utils::phpTimestampToHTMLTime(Utils::sqlDateTimeToPHPTimestamp($result["fecha_ingreso"]));		
			//$row[] = '<a target="_blank" href="../modiIEH.php3?COD_CENTRO=' . $centro . '&TIPO_DOC=' . Utils::sqlIntToPHP($result["cod_doc"]) . '&NRO_DOC=' . Utils::sqlIntToPHP($result["nro_doc"]) . '&dia_ingreso=' . $fecha_ingreso . '&hora_ingreso=' . $hora_ingreso . '">Detalle</a>';  //Para el boton de detalle
			
		}
		$this->baseDeDatos->desconectar();
		
		$docExcel->send($filename);
		$docExcel->close();
	}
	
	/**
	 * 
	 * Devuelve TRUE cuando el sector fisico corresponde a una sala HCA (UDP / DAP)
	 * @param int $cod_secfis
	 * @return bool
	 */
	function esSalaHCA($cod_secfis)
	{
	   $sectoresHCA[] = 199;	//UDP Traumato
	   $sectoresHCA[] = 207;	//UDP Clinica
	   $sectoresHCA[] = 234;	//UDP Cirugia
	   $sectoresHCA[] = 228;  	//UDP Pediatria
	   $sectoresHCA[] = 238;  	//UDP Pediatria, esta se tiene que ir
	   //$sectoresHCA[] = 236;  	//UDP ORL Materno 
	   $sectoresHCA[] = 237;  	//UDP Urologia 
	   $sectoresHCA[] = 239;  	//UDP Cardilogia agudos
	   $sectoresHCA[] = 240;  	//UDP Cardiologia
	   $sectoresHCA[] = 249;  	//UDP Dermatologia
	   $sectoresHCA[] = 105;  	//UDP Shockroom
	   $sectoresHCA[] = 255;	//UDP ORL
	   $sectoresHCA[] = 256;    //DAP OBSERVACION DENGUE
	   
	   $ret = in_array($cod_secfis, $sectoresHCA);
	   
	   return $ret;
	}
	
	
	
function queryPacientesUDPNacer($count, $page, $limit,$sidx,$sord,$fechaInicio,$fechaFin,$where=null)
	{
		if( $count >0 ) 
			{ 
				$total_pages = ceil($count/$limit); 
			} 
		else 
			{ 
				$total_pages = 0; 
			}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
			 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if ($start<0) {
			$start = 0;
		}
		
		
		$query = "select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					loc.detalle as localidad,
					fp.direccion as direccion,
					hca.fecha_ingreso as ingreso,
					hs.fecha_creacion as egreso,
					ifnull(fp.sexo,'N') as sexo,
					hca.motivo_consulta as motivo,
					fp.fechanac as fechaNacimiento,
					YEAR(hca.fecha_ingreso) - YEAR(fp.fechanac) + IF(MONTH(hca.fecha_ingreso) > MONTH(fp.fechanac), 0, -1) as edad,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta UDP') as destinoUDP,
					os.cod_busq as obraSocial,
					if(hstd.nombre is NULL,ifnull(sfi.cod_secfis, sfs.cod_secfis),sfs.cod_secfis) as cod_secfis, 
					timediff(hs.fecha_creacion,hca.fecha_ingreso) as tiempo,
					hca.interv_policial as ip
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					localidades loc on (fp.cod_locali = loc.cod_locali) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id)left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (hca.cod_osoc = os.cod_osoc) left join
					diaglocal dl on (dl.id_diagno = hs.cod_diagno) left join
					incli ic on (ic.tipodoc = hca.tipodoc and ic.nrodoc = hca.nrodoc and ic.fecha_ingreso = hca.fecha_ingreso) left join
					insecfis sfs on (hs.cod_secfis = sfs.cod_secfis) left join
					insecfis sfi on (ic.cod_secfis = sfi.cod_secfis)
				 WHERE 
				 	hca.fecha_ingreso BETWEEN ".Utils::phpTimestampToSQLDate($fechaInicio)." AND ".Utils::phpTimestampToSQLDate($fechaFin)."
				 	";
		if($where!=null)
		{
			$query = $query . $where;
		}
		
		$_SESSION['pacientesUDPNacer'] = $query . " ORDER BY $sidx $sord;";
		
		$this->firephp->log($_SESSION['pacientesUDPNacer'], "Pacientes");
		
		$query = $query . " ORDER BY $sidx $sord LIMIT $start , $limit;";
				
		//$this->firephp->log($query,"Consulta UDP");
		return $query;
	}
	
	/**
	 * 
	 * Devuelve un JSON con los datos de los pacientes de UDP del mes especificado
	 * @return JSON $pacientesUDP
	 */

	function pacientesUDPNacer($page, $limit, $sidx,$sord,$fechaIni,$fechaFin,$where=null) 
	{
		//TODO: si llego a tener limit lo pasaria por aca
		$count = $this->cantidadPacientesUDPNacer($fechaIni, $fechaFin,$where);
		$query = $this->queryPacientesUDPNacer($count, $page, $limit, $sidx, $sord, $fechaIni,$fechaFin,$where);
		
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			//$this->firephp->log($query, 'Ejecutando Query');
			//$this->firephp->error($e);
			throw $e;
		}
		
		
		//CONTEO DE LAS PAGINAS Y TOTALES
		if( $count >0 ) 
		{ 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		}
		
		if ($page > $total_pages)
		{
			$page=$total_pages;
		}
		//FIN CONTEO DE LAS PAGINAS Y TOTALES	 
		
		$response->page = $page; 
		$response->total = $total_pages; 
		$response->records = $count;
		 
		for ($i = 0; $i < $this->baseDeDatos->querySize(); $i++) {
			$result = $this->baseDeDatos->fetchRow();
			$response->rows[$i]['id'] = Utils::sqlIntToPHP($result["id"]);			
			$row = array();
			
			$row[] = Utils::sqlIntToPHP($result["tipodoc"]);
			$row[] = Utils::sqlIntToPHP($result["nrodoc"]);
			$row[] = Utils::phpStringToHTML($result["nombre"]);
			$row[] = Utils::phpStringToHTML($result["localidad"]);
			$row[] = Utils::phpStringToHTML($result["direccion"]);
			$row[] = Utils::phpStringToHTML($result["sexo"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["fechaNacimiento"]));
			$row[] = Utils::sqlIntToPHP($result["edad"]);
//			$row[] = ($result["ingreso"]);
			$row[] = Utils::phpTimestampToHTMLDate(Utils::sqlDateTimeToPHPTimestamp($result["ingreso"]));
			
			
			
			
			
			$sector = Utils::sqlIntToPHP($result["cod_secfis"]);
			
			switch ($sector) {
				case 234:
					$texto = "UDP Cirugia";
					break;
					
				case 207:
					$texto = "UDP Clinica";
					break;
					
				case 199:
					$texto = "UDP Traumatologia";
					break;
				
				case 228:
					$texto = "UDP Pediatria";
					break;
					
				case 236:
					$texto = "UDP ORL Materno";
					break;
					
				case 237:
					$texto = "UDP UROLOGIA";
					break;

				case 238:
					$texto = "UDP Pediatria Clínica";
					break;

				case 239:
					$texto = "UDP Cardiología Agudos";
					break;
					
				case 240:
					$texto = "UDP Cardiología";
					break;

				case 229:
					$texto = "UDP Pami";
					break;

				case 249:
					$texto = "UDP Dermatologia";
					break;

				case 249:
					$texto = "Shockroom";
					break;

				case 255:
					$texto = "UDP ORL Abete";
					break;
					
				default:
					$this->firephp->log($sector,"SECTOR");
					$texto = "N/A";
				break;
			}
			
			$row[] = Utils::phpStringToHTML($texto);
			
			
			$row[] = htmlentities($result["destinoUDP"]);
			$row[] = Utils::phpStringToHTML($result["diagEgreso"]);
			$row[] = Utils::phpStringToHTML($result["profesional"]);
			
			$row[] = Utils::phpStringToHTML($result["obraSocial"]);
			if(Utils::sqlBoolToPHP($result["ip"]))
			{
				$ip = "Si";
			}
			else 
			{
				$ip = "No";
			}
			$row[] = htmlentities($ip);
			
			
			$row[] = '';  //Para el boton seleccionar
			$response->rows[$i]['cell'] = $row;

			
		}
		
		$this->baseDeDatos->desconectar();
		
		return json_encode($response);
		
	}
	
	function cantidadPacientesUDPNacer($fechaInicio, $fechaFin,$where)
	{
		
		$query = "select count(*) as cantidad from (
				select 
					hca.id as id,
					hca.tipodoc as tipodoc, 
					hca.nrodoc as nrodoc,
					fp.nombre as nombre, 
					loc.detalle as localidad,
					hca.fecha_ingreso as ingreso,
					hs.fecha_creacion as egreso,
					ifnull(fp.sexo,'N') as sexo,
					hca.motivo_consulta as motivo,
					YEAR(hca.fecha_ingreso) - YEAR(fp.fechanac) + IF(MONTH(hca.fecha_ingreso) > MONTH(fp.fechanac), 0, -1) as edad,
					ifnull(if(hs.cod_diagno >100000,cie.dec10,dl.diag_local),'#N/A') as diagEgreso,
					ifnull(p.nombre,'#N/A') as profesional,
					ifnull(hstd.nombre,'Sin Alta UDP') as destinoUDP,
					os.cod_busq as obraSocial,
					if(hstd.nombre is NULL,ifnull(sfi.cod_secfis, sfs.cod_secfis),sfs.cod_secfis) as cod_secfis, 
					timediff(hs.fecha_creacion,hca.fecha_ingreso) as tiempo,
					hca.interv_policial as ip
				from
					hca left join 
					fichaspacientes fp on (hca.tipodoc = fp.tipodoc and hca.nrodoc = fp.nrodoc) left join
					localidades loc on (fp.cod_locali = loc.cod_locali) left join
					hca_tipo_destino htd on (hca.tipo_destino = htd.id) left join
					hca_salida hs on (hs.hca_id = hca.id and hs.estado='ACTIVO') left join
					hca_salida_tipo_destino hstd on (hstd.id = hs.tipo_destino_id)left join
					cie10 cie on (cie.id = hs.cod_diagno) left join
					profesionales p on (hs.cod_profes = p.cod_profes) left join
					IEH ieh on (hca.tipodoc = ieh.tipodoc and hca.nrodoc = ieh.nrodoc and hca.fecha_ingreso = ieh.fecha_ingreso) left join
					obrassociales os on (hca.cod_osoc = os.cod_osoc) left join
					diaglocal dl on (dl.id_diagno = hs.cod_diagno) left join
					incli ic on (ic.tipodoc = hca.tipodoc and ic.nrodoc = hca.nrodoc and ic.fecha_ingreso = hca.fecha_ingreso) left join
					insecfis sfs on (hs.cod_secfis = sfs.cod_secfis) left join
					insecfis sfi on (ic.cod_secfis = sfi.cod_secfis)
				 WHERE 
				 	hca.fecha_ingreso BETWEEN ".Utils::phpTimestampToSQLDate($fechaInicio)." AND ".Utils::phpTimestampToSQLDate($fechaFin)."
				 	";
		if($where!=null)
		{
			$query = $query . $where;
		}
		
		$query = $query . ") consulta;";
		$this->firephp->log($query,"Query cantidades");
		try
		{	
			$this->baseDeDatos->conectar();
			$this->baseDeDatos->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
		$result = $this->baseDeDatos->fetchRow();
		$cantidad = Utils::sqlIntToPHP($result['cantidad']);
		
		return $cantidad;
	}
}