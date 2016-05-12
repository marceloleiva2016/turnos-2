<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcConexion . 'conectionData.php';
include_once nspcCommons . 'dataBaseConnector.php';
include_once nspcCommons.'generalesDataBaseLinker.php';
include_once nspcCommons . 'utils.php';
include_once nspcInternacion . 'internacion.class.php';
include_once nspcPacientes . 'paciente.class.php';
include_once 'intervencionMenor.class.php';
include_once 'cronicaIntervencion.class.php';
include_once 'observacion.class.php';
include_once 'itemObservacion.class.php';

class EpicrisisDatabaseLinker
{
	private $DB_epicrisis;

//-------------------------------------Constructor--------------------------------------------

	public function EpicrisisDatabaseLinker()
	{
		$this->DB_epicrisis = new dataBaseConnector(HOST,0,DBvieja,USRDBvieja,PASSDBvieja);
		if (!isset($_SESSION)) 
		{
			session_start();
		}
	}

    /**
	 * Retorna verdadero si se genero una epicrisis para el paciente internado
	 * @param int $tipodoc
	 * @param int $nrodoc
     * @param String $fecha_ingreso
	 * @throws Exception
	 * @return boolean
     */
    //TODO: por ahora no le encuentro utilidad
    public function tieneEpicrisis($tipodoc ,$nrodoc ,$fecha_ingreso)
    {
    	$query="SELECT
    				id
    			FROM
    				epicrisis
    			WHERE
    				tipodoc=".$tipodoc." AND
    				nrodoc=".$nrodoc." AND
    				fecha_ingreso=".Utils::phpTimestampToSQLDatetime($fecha_ingreso)." AND
                    habilitado=1;";
    	try
		{
			$this->DB_epicrisis->conectar();
			$this->DB_epicrisis->ejecutarQuery($query);
		}
		catch (Exception $e)
		{
            $this->DB_epicrisis->desconectar();
			throw new Exception("Error al conectar con la base de datos o hacer la consulta", 17052013);
		}

		$result = $this->DB_epicrisis->fetchRow($query);
		$this->DB_epicrisis->desconectar();

		if($result!=false)
		{
			return true;
		}

		return $result;
    }
    
    /**
	 * 
	 * Devuelve el Id de la Epicrisis
	 * @param int $fecha_ingreso
	 * @param int $tipoDoc
	 * @param int $nroDoc 
	 * @throws Exception
	 * @return int 
	 */
	public function getIdEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso)
	{
		$sql = "SELECT
					id 
				FROM
                    epicrisis
				WHERE
					tipodoc = ".Utils::phpIntToSQL($tipoDoc)." AND
					nrodoc = ".Utils::phpIntToSQL($nroDoc)." AND
					fecha_ingreso = '".$fecha_ingreso."' AND
                    habilitado=1;";
		try
		{
			$this->DB_epicrisis->conectar();
			$this->DB_epicrisis->ejecutarQuery($sql);
		}
		catch (Exception $e)
		{
            $this->DB_epicrisis->desconectar();
			throw new Exception("No se pudo traer el id de epicrisis", 201230);
		}
		
		$result = $this->DB_epicrisis->fetchRow();
        $this->DB_epicrisis->desconectar();
            $id = Utils::sqlIntToPHP($result['id']);
            return $id;
	}

    /**
     * genero una epicrisis nueva
     * @param Epicrisis $epi
     */
    public function generarEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso)
    {
        try
        {
            $cod_osoc = $this->obtenerObraSocialInternacion($tipoDoc, $nroDoc, $fecha_ingreso);
            $cod_diagno_ingreso = $this->obtenerCodDiagIngreso($tipoDoc, $nroDoc, $fecha_ingreso);
        } 
        catch (Exception $e)
        {
            throw new Exception("No se pudo traer el codigo de obra social de la epicrisis", 201230);
        }

        $query="INSERT INTO 
                    epicrisis
                        (`iduser`, 
                        `fecha_creacion`, 
                        `tipodoc`, 
                        `nrodoc`,
                        `fecha_ingreso`,
                        `cod_diagno_ingreso`,
                        `cod_osoc`,
                        `habilitado`)
                VALUES 
                    ('1', 
                    now(), 
                    ".Utils::phpIntToSQL($tipoDoc).",
                    ".Utils::phpIntToSQL($nroDoc).",
                    '".$fecha_ingreso."',
                    ".Utils::phpStringToSQL($cod_diagno_ingreso).",
                    ".Utils::phpIntToSQL($cod_osoc).",
                    '1');";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo crear la epicrisis", 201230);
        }

        return true;
    }

    public function obtenerObraSocialInternacion($tipodoc, $nrodoc, $fecha_ingreso)
    {
        $query="SELECT 
                    cod_osoc
                FROM 
                    IEH
                WHERE 
                    tipodoc = ".Utils::phpIntToSQL($tipodoc)." AND 
                    nrodoc = ".Utils::phpIntToSQL($nrodoc)." AND 
                    fecha_ingreso = '".$fecha_ingreso."';";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer el id de epicrisis", 201230);
        }
        
        $result = $this->DB_epicrisis->fetchRow();
        $this->DB_epicrisis->desconectar();
        $id = Utils::sqlIntToPHP($result['cod_osoc']);
        return $id;
    }

    private function obtenerCodDiagIngreso($tipoDoc, $nroDoc, $fecha_ingreso)
    {
        $query="SELECT
                    cod_diagno
                FROM  
                    himef
                WHERE 
                    tipodoc = " . Utils::phpIntToSQL($tipoDoc) . " AND 
                    nrodoc = " . Utils::phpIntToSQL($nroDoc) . " AND 
                    fecha_ingreso = '".$fecha_ingreso."' AND
                    motivo = '5';";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer el codigo del diagnostico de ingreso", 201230);
        }
        
        $result = $this->DB_epicrisis->fetchRow();
        $this->DB_epicrisis->desconectar();
        $codigo = $result['cod_diagno'];

        return $codigo;
    }

//--------------------------------------------Funciones de carga de epicrisis-----------------------------------------------//

    /**
     * Carga los datos iniciales de una epicrisis
     */
    public function cargarDatosEpicrisis(Epicrisis $epi)
    {
        $this->cargarVariablesLocales($epi);
        $this->cargarInternacion($epi);
        $epi->internacion->cargarTodo();
        $this->cargarAntecedentes($epi);
        $this->cargarMedicacionHabitual($epi);
        $this->cargarCronicaIntervenciones($epi);
        $this->cargarExamenesComplementarios($epi);
        $this->cargarIntervencionesMenores($epi);
        $this->cargarObservaciones($epi);
        $this->cargarDestino($epi);
    }

    /**
     * Crea la internacion asignada a la epicrisis
     * @param int $idEpicrisis
     * @throws Exception
     * @return IdInternacion
     */
    private function cargarInternacion(Epicrisis $epi)
    {
        
        $sql = "SELECT
                    tipodoc,
                    nrodoc,
                    fecha_ingreso
                FROM
                    epicrisis
                WHERE
                    id = ".Utils::phpIntToSQL($epi->getId()).";";
        
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la informacion del internado", 201230);
        }
        
        $result = $this->DB_epicrisis->fetchRow();
        $tipoDoc = Utils::sqlIntToPHP($result['tipodoc']);
        $nroDoc = Utils::sqlIntToPHP($result['nrodoc']);
        $fechaIngreso = Utils::sqlDateTimeToPHPTimestamp($result['fecha_ingreso']);
        
        $epi->internacion = new Internacion($tipoDoc, $nroDoc, $fechaIngreso);

        $this->DB_epicrisis->desconectar();

        return true;

        $this->DB_epicrisis->desconectar();
    }

    private function cargarAntecedentes(Epicrisis $epi)
    {
        $query="SELECT
                    ta.id,
                    ta.detalle AS antecedente,
                    a.descripcion
                FROM 
                    epicrisis_antecedentes a JOIN
                    epicrisis_tipo_antecedente ta ON(a.idantecedente=ta.id)
                WHERE
                    a.idepicrisis=".Utils::phpIntToSQL($epi->getId())." AND
                    a.habilitado=1;";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los antecedentes de la epìcrisis", 201230);
        }

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $antecedente = $this->DB_epicrisis->fetchRow($query);
            $epi->agregarAntecedente($antecedente['id'], $antecedente['antecedente'], $antecedente['descripcion']);
        }

        $this->DB_epicrisis->desconectar();
    }

    public function cargarVariablesLocales(Epicrisis $epi)
    {
        $query="SELECT
                    estado_ingreso,
                    cod_diagno_ingreso,
                    cod_osoc
                FROM
                    epicrisis
                WHERE
                    id=".Utils::phpIntToSQL($epi->getId()).";";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los estados no cargados de la epìcrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow($query);

        $epi->setIngreso($result['estado_ingreso']);
        $epi->setCodDiagnoIngreso($result['cod_diagno_ingreso']);
        $epi->setObraSocial($result['cod_osoc']);

        $this->DB_epicrisis->desconectar();
    }

    public function cargarCronicaIntervenciones(Epicrisis $epi)
    {
        $query="SELECT
                    im.id AS id,
                    im.descripcion AS descripcion,
                    IF(i.idtipo_intervencion,'True','False') AS seleccionado
                FROM
                    epicrisis_cronica_intervenciones i RIGHT JOIN
                    epicrisis_tipo_cronica_intervencion im ON(im.id=i.idtipo_intervencion AND i.idepicrisis=".Utils::phpIntToSQL($epi->getId()).")
                WHERE
                    im.habilitado=1;";
         try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las intervenciones selecionadas para la epìcrisis", 201230);
        }

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $intervencion = $this->DB_epicrisis->fetchRow($query);
            $epi->agregarCronicaIntervencion($intervencion['id'], $intervencion['descripcion'], $intervencion['seleccionado']);
        }

        $this->DB_epicrisis->desconectar();
    }

    private function cargarExamenesComplementarios(Epicrisis $epi)
    {
        $query="SELECT
                    te.id,
                    te.detalle AS examen,
                    e.descripcion
                FROM 
                    epicrisis_examenes e JOIN
                    epicrisis_tipo_examen te ON(e.idtipo_examen=te.id)
                WHERE
                    e.idepicrisis=".Utils::phpIntToSQL($epi->getId()).";";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los antecedentes de la epìcrisis", 201230);
        }

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $examen = $this->DB_epicrisis->fetchRow($query);
            $epi->agregarExamenComplementario($examen['id'], $examen['examen'], $examen['descripcion']);
        }

        $this->DB_epicrisis->desconectar();
    }

    public function cargarIntervencionesMenores(Epicrisis $epi)
    {
        $query="SELECT
                    im.id AS id,
                    im.descripcion AS descripcion,
                    IF(i.idtipo_intervencion,'True','False') AS seleccionado
                FROM
                    epicrisis_intervenciones_menores i RIGHT JOIN
                    epicrisis_tipo_intervencion_menor im ON(im.id=i.idtipo_intervencion AND i.idepicrisis=".Utils::phpIntToSQL($epi->getId()).")
                WHERE
                    im.habilitado=1;";
         try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las intervenciones selecionadas para la epìcrisis", 201230);
        }

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $intervencion = $this->DB_epicrisis->fetchRow($query);
            $epi->agregarIntervencionMenor($intervencion['id'], $intervencion['descripcion'], $intervencion['seleccionado']);
        }

        $this->DB_epicrisis->desconectar();
    }

    public function cargarObservaciones(Epicrisis $epi)
    {
        $observaciones = $this->traerTiposObservaciones($epi->getId());
    
        for ($i=0; $i < count($observaciones); $i++)
        {
            $query="SELECT
                        eo.id AS id,
                        eo.detalle AS detalle,
                        eo.fecha_ingreso AS fecha,
                        u.detalle AS usuario
                    FROM 
                        epicrisis_observaciones eo JOIN
                        user_usuario u ON(eo.idusuario=u.iduser_usuario)
                    WHERE
                        eo.idepicrisis=".Utils::phpIntToSQL($epi->getId())." AND
                        eo.idtipo_observacion=".Utils::phpIntToSQL($observaciones[$i]->getTipo())." AND
                        eo.habilitado=1;   ";

            $this->DB_epicrisis->conectar();
            try
            {
                $this->DB_epicrisis->ejecutarQuery($query);
            }
            catch (Exception $e)
            {
                $this->DB_epicrisis->desconectar();
                throw new Exception("No se pudo traer items de la observacion ".$observaciones[$i]->getDetalle()." para la epicrisis con id = ".$epi->getId().", Por favor comuniquese con informatica con esa informacion de error. Gracias", 201230);
            }

            for ($f = 0; $f < $this->DB_epicrisis->querySize; $f++)
            {
                $result = $this->DB_epicrisis->fetchRow($query);
                $fecha = Utils::phpTimestampToHTMLDateTime(Utils::sqlDateTimeToPHPTimestamp($result['fecha']));
                $observaciones[$i]->agregarItem($result['id'], $fecha, $result['usuario'], $result['detalle']);
            }

            $this->DB_epicrisis->desconectar();
        }
    
        $epi->agregarObservaciones($observaciones);

        $this->DB_epicrisis->desconectar();
    }

    public function cargarDestino(Epicrisis $epi)
    {
        $query="SELECT
                    diagno_egreso,
                    destino,
                    fecha_creacion
                FROM
                    epicrisis_egresos
                WHERE
                    idepicrisis=".Utils::phpIntToSQL($epi->getId())." AND
                    habilitado=1;";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los estados no cargados de la epìcrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow($query);

        $epi->setDestino($result['destino']);
        $epi->setFechaEgreso($result['fecha_creacion']);
        $epi->setDiagnosticoEgreso($result['diagno_egreso']);
        $this->DB_epicrisis->desconectar();
    }

    public function cargarMedicacionHabitual(Epicrisis $epi)
    {
        $query="SELECT 
                    detalle 
                FROM 
                    epicrisis_medicacion_habitual 
                WHERE 
                    idepicrisis=".Utils::phpIntToSQL($epi->getId())." AND
                    habilitado='1';";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las intervenciones selecionadas para la epìcrisis", 201230);
        }

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $result = $this->DB_epicrisis->fetchRow($query);
            $epi->agregarMedicacionHabitual($result['detalle']);
        }

        $this->DB_epicrisis->desconectar();
    }

//---------------------------------funciones para formularios de insersion en la epicrisis-------------------------------//

    public function obtenerAntecedentesNoCargados($epiId)
    {
        $query="SELECT 
                    id,
                    detalle
                FROM 
                    epicrisis_tipo_antecedente
                WHERE 
                    epicrisis_tipo_antecedente.id 
                    NOT IN(
                        SELECT
                            idantecedente
                        FROM 
                            epicrisis_antecedentes
                        WHERE
                            idepicrisis=".Utils::phpIntToSQL($epiId)." AND
                            habilitado=1 );";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los antecedentes no cargados de la epìcrisis", 201230);
        }

        $antecedentes = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $antecedente = $this->DB_epicrisis->fetchRow($query);
            $antecedentes[] = $antecedente;
        }

        return $antecedentes;
    }

    public function ingresarAntecedente($epiId, $idAnt, $detalle, $iduser)
    {
        $query="INSERT INTO
                    epicrisis_antecedentes(
                        `idepicrisis`,
                        `idantecedente`,
                        `descripcion`,
                        `fecha_creacion`,
                        `idusuario`,
                        `habilitado`) 
                    VALUES(
                        ".Utils::phpIntToSQL($epiId).",
                        ".Utils::phpIntToSQL($idAnt).",
                        ".Utils::phpStringToSQL($detalle).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
                        '1');";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo ingresar el atecedente a la epìcrisis", 201230);
        }

        return true;
    }

    public function estadosDeIngreso($idEpicrisis)
    {
        $estados = array();
  
        $query="SELECT
                    id,
                    detalle
                FROM
                    epicrisis_estadoIngreso;";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los estados para la epìcrisis", 201230);
        }


        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $estado = $this->DB_epicrisis->fetchRow($query);
            $estados[] = $estado;
        }    
        
        return $estados;
    }

    public function ingresarEstadoIngreso($idEpicrisis, $idEstadoIngreso)
    {
        $query="UPDATE 
                    epicrisis 
                SET 
                    estado_ingreso=".Utils::phpIntToSQL($idEstadoIngreso)." 
                WHERE 
                    id=".Utils::phpIntToSQL($idEpicrisis).";";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo ingresar el estado de ingreso a la epicrisis", 201230);
        }
        $this->DB_epicrisis->desconectar();

        return true;
    }

    public function obtenerCronicaIntervenciones($idEpicrisis)
    {
        $query="SELECT
                    im.id AS id,
                    im.descripcion AS descripcion,
                    IF(i.idtipo_intervencion,'True','False') AS seleccionado
                FROM
                    epicrisis_cronica_intervenciones i RIGHT JOIN
                    epicrisis_tipo_cronica_intervencion im ON(im.id=i.idtipo_intervencion AND i.idepicrisis=".Utils::phpIntToSQL($idEpicrisis).")
                WHERE
                    im.habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las intervenciones selecionadas para la epìcrisis", 201230);
        }

        $intervenciones = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $intervencion = $this->DB_epicrisis->fetchRow($query);
            $intervenciones[] = new CronicaIntervencion($intervencion['id'], $intervencion['descripcion'], $intervencion['seleccionado']);
        }

        return $intervenciones;
    }

    public function actualizarCronicaIntervenciones($data, $iduser)
    {
        $intervEpicrisis = $this->obtenerCronicaIntervenciones($data['id']);

        $sql="DELETE FROM epicrisis_cronica_intervenciones WHERE idepicrisis=".Utils::phpIntToSQL($data['id']).";";

        $this->DB_epicrisis->conectar();

        try
        {
            $this->DB_epicrisis->ejecutarAccion($sql);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se puedo ejecutar el primer paso de la actualizacion de intervenciones", 201230);
        }

        for ($i=0; $i < count($data['cronicaIntervenciones']); $i++) 
        { 
            $query="INSERT INTO epicrisis_cronica_intervenciones
                        (
                            idepicrisis, 
                            idtipo_intervencion, 
                            idusuario, 
                            fecha_creacion
                        ) 
                    VALUES
                        (
                            ".Utils::phpIntToSQL($data['id']).", 
                            ".Utils::phpIntToSQL($data['cronicaIntervenciones'][$i]).", 
                            ".Utils::phpStringToSQL($iduser).",
                            now()
                        );";

            try
            {
                $this->DB_epicrisis->ejecutarAccion($query);
            }
            catch (Exception $e)
            {
                $this->DB_epicrisis->desconectar();
                throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
            }
        }
        return true;
    }

    public function obtenerTiposExamenesNoCargados($epiId)
    {
        $query="SELECT 
                    id,
                    detalle
                FROM
                    epicrisis_tipo_examen
                WHERE
                    habilitado=1 AND
                    epicrisis_tipo_examen.id NOT IN(
                        SELECT
                            idtipo_examen
                        FROM 
                            epicrisis_examenes
                        WHERE
                            idepicrisis=".Utils::phpIntToSQL($epiId).");";

        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los examenes para la epìcrisis", 201230);
        }

        $examenes = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $examen = $this->DB_epicrisis->fetchRow($query);
            $examenes[] = $examen;
        }
        
        return $examenes;
    }

    public function ingresarExamenComplementario($epiId, $idTipoExamen, $descripcion, $iduser)
    {
        $query="INSERT INTO
                    epicrisis_examenes
                        (
                        `idepicrisis`,
                        `idtipo_examen`,
                        `descripcion`,
                        `idusuario`,
                        `fecha_ingreso`
                        ) 
                    VALUES
                        (
                        ".Utils::phpIntToSQL($epiId).",
                        ".Utils::phpIntToSQL($idTipoExamen).",
                        ".Utils::phpStringToSQL($descripcion).",
                        ".Utils::phpIntToSQL($iduser).",
                        now()
                        );";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo ingresar el atecedente a la epìcrisis", 201230);
        }

        return true;
    }

    public function obtenerIntervencionesMenores($idEpicrisis)
    {
        $query="SELECT
                    im.id AS id,
                    im.descripcion AS descripcion,
                    IF(i.idtipo_intervencion,'True','False') AS seleccionado
                FROM
                    epicrisis_intervenciones_menores i RIGHT JOIN
                    epicrisis_tipo_intervencion_menor im ON(im.id=i.idtipo_intervencion AND i.idepicrisis=".Utils::phpIntToSQL($idEpicrisis).")
                WHERE
                    im.habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las intervenciones selecionadas para la epìcrisis", 201230);
        }

        $intervenciones = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $intervencion = $this->DB_epicrisis->fetchRow($query);
            $intervenciones[] = new IntervencionMenor($intervencion['id'], $intervencion['descripcion'], $intervencion['seleccionado']);
        }

        return $intervenciones;
    }

    public function actualizarIntervencionesMenores($data, $iduser)
    {
        $intervEpicrisis = $this->obtenerIntervencionesMenores($data['id']);

        $sql="DELETE FROM epicrisis_intervenciones_menores WHERE idepicrisis=".Utils::phpIntToSQL($data['id']).";";

        $this->DB_epicrisis->conectar();

        try
        {
            $this->DB_epicrisis->ejecutarAccion($sql);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se puedo ejecutar el primer paso de la actualizacion de intervenciones", 201230);
        }

        for ($i=0; $i < count($data['intervenciones']); $i++) 
        { 
            $query="INSERT INTO epicrisis_intervenciones_menores
                        (
                            idepicrisis, 
                            idtipo_intervencion, 
                            idusuario, 
                            fecha_creacion
                        ) 
                    VALUES
                        (
                            '".Utils::phpIntToSQL($data['id'])."', 
                            '".Utils::phpIntToSQL($data['intervenciones'][$i])."', 
                            ".Utils::phpStringToSQL($iduser).",
                            now()
                        );";

            try
            {
                $this->DB_epicrisis->ejecutarAccion($query);
            }
            catch (Exception $e)
            {
                $this->DB_epicrisis->desconectar();
                throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
            }
        }
        return true;
    }

    public function insertarObservacion($idEpicrisis, $tipoObs, $texto, $iduser)
    {
        $query="INSERT INTO 
                    epicrisis_observaciones
                    (
                        `idepicrisis`,
                        `idtipo_observacion`,
                        `detalle`,
                        `fecha_ingreso`,
                        `idusuario`,
                        `habilitado`
                    )
                VALUES
                    (
                        ".Utils::phpIntToSQL($idEpicrisis).",
                        ".Utils::phpIntToSQL($tipoObs).",
                        ".Utils::phpStringToSQL($texto).",
                        now(),
                        ".Utils::phpStringToSQL($iduser).",
                        '1'
                    );";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
        }

        $this->DB_epicrisis->desconectar();
        return true;
    }

    public function nombreTipoObservacion($idTipoObs)
    {
        $query="SELECT
                    detalle
                FROM
                    epicrisis_tipo_observacion
                WHERE 
                    id=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las observaciones selecionadas para la epìcrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow($query);

        return $result['detalle'];
    }

    public function traerTiposObservaciones($idEpicrisis)
    {
        $query="SELECT
                    eto.id,
                    eto.detalle
                FROM
                    epicrisis_tipo_observacion eto
                WHERE
                    eto.id IN(SELECT
                                DISTINCT(eo.idtipo_observacion) AS id
                            FROM
                                epicrisis_observaciones eo
                            WHERE
                                eo.idepicrisis=".Utils::phpIntToSQL($idEpicrisis)." AND
                                eo.habilitado=1);";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $observaciones = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $result = $this->DB_epicrisis->fetchRow($query);
            $observacion = new Observacion();
            $observacion->setTipo($result['id']);
            $observacion->setDetalle($result['detalle']);
            $observaciones[] = $observacion;
        }

        $this->DB_epicrisis->desconectar();

        return $observaciones;
    }

    public function obtenerCantidadObservacionesDeTipo($idEpicrisis, $idTipoObs)
    {
        $query="SELECT 
                    count(*) AS cantidad
                FROM 
                    epicrisis_observaciones eo JOIN
                    user_usuario u ON(eo.idusuario=u.iduser_usuario)
                WHERE
                    idepicrisis=".Utils::phpIntToSQL($idEpicrisis)." AND
                    idtipo_observacion=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow($query);

        $this->DB_epicrisis->desconectar();

        return Utils::sqlIntToPHP($result['cantidad']);
    }

    public function destinosDeEpicrisis($idEpicrisis)
    {
        $sql="SELECT
                    destino
                FROM
                    epicrisis_egresos
                WHERE
                    idepicrisis=".Utils::phpIntToSQL($idEpicrisis)." AND 
                    habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer el destino cargado de la epìcrisis", 201230);
        }

        $destino = $this->DB_epicrisis->fetchRow($sql);

        $destinos = array();

        if($destino['destino']==NULL)
        {
            $query="SELECT
                        id,
                        detalle
                    FROM
                        epicrisis_destino;";

            try
            {
                $this->DB_epicrisis->conectar();
                $this->DB_epicrisis->ejecutarQuery($query);
            }
            catch (Exception $e)
            {
                $this->DB_epicrisis->desconectar();
                throw new Exception("No se pudo traer los destinos para la epìcrisis", 201230);
            }


            for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
            {
                $salida = $this->DB_epicrisis->fetchRow($query);
                $destinos[] = $salida;
            }
        }
        
        return $destinos;
    }

    public function ingresarEgreso($idEpicrisis, $idDestino, $cod_diagno, $iduser)
    {
        $query="INSERT INTO
                    epicrisis_egresos(
                            `idepicrisis`,
                            `diagno_egreso`,
                            `destino`,
                            `fecha_creacion`,
                            `habilitado`,
                            `idusuario`)
                VALUES (".Utils::phpIntToSQL($idEpicrisis).",
                        ".Utils::phpIntToSQL($cod_diagno).",
                        ".Utils::phpIntToSQL($idDestino).",
                        now(),
                        '1',
                        ".Utils::phpIntToSQL($iduser).");";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo ingresar el destino a la epicrisis", 201230);
        }
        $this->DB_epicrisis->desconectar();

        return true;
    }

    public function anosConEpicrisis()
    {
        $query="SELECT 
                    DISTINCT(YEAR(fecha_ingreso)) AS ano 
                FROM 
                    epicrisis
                WHERE
                    habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los anios de las epicrisis cargadas", 201230);
        }

        $anos = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $result = $this->DB_epicrisis->fetchRow($query);
            $anos[] = $result['ano'];
        }

        $this->DB_epicrisis->desconectar();

        return $anos;
    }

    public function mesesConEpicrisis()
    {
        $query="SELECT 
                    DISTINCT(MONTH(fecha_ingreso)) AS mes 
                FROM 
                    epicrisis
                WHERE
                    habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer los mesess de las epicrisis cargadas", 201230);
        }

        $meses = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $result = $this->DB_epicrisis->fetchRow($query);
            $meses[] = $result['mes'];
        }

        $this->DB_epicrisis->desconectar();

        return $meses;
    }

    public function ingresarMedicacionHabitual($idepicrisis, $detalle, $idusuario)
    {
        $query="INSERT INTO
                        epicrisis_medicacion_habitual(
                            `idepicrisis`,
                            `detalle`,
                            `fecha_ingreso`,
                            `habilitado`,
                            `usuario`)
                    VALUES
                        ('".$idepicrisis."',
                            '".$detalle."',
                            now(),
                            '1',
                            ".$idusuario.");";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo ingresar la medicacion habitual", 201230);
        }
        $this->DB_epicrisis->desconectar();

        return true;
    }

    public function obtenerCantidadMedicacionHabitual($idepicrisis)
    {
        $query="SELECT
                    count(id) AS cantidad 
                FROM
                    epicrisis_medicacion_habitual
                WHERE
                    idepicrisis=".$idepicrisis." AND 
                    habilitado=1;";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la cantidad de medicina habitual de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow($query);

        $this->DB_epicrisis->desconectar();

        return Utils::sqlIntToPHP($result['cantidad']);
    }

    public function getInternados($centro ,$page, $rows, $filters, $sidx, $sord)
    {
        $where = " AND i.cod_centro = '".$centro."' ";

        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=$filters['rules'][$i]['field']." REGEXP '".$filters['rules'][$i]['data']."' ";
            }
        }

        $sort = "ORDER BY ".$sidx." ".$sord;

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    inc.cod_cama AS cama,
                    td.nombre_corto AS tipodoc,
                    inc.nrodoc AS nrodoc,
                    fp.nombre AS paciente,
                    fp.sexo AS sexo,
                    sfis.des_secfis AS sector,
                    dg.diag_local AS diagnostico,
                    e.detalle AS especialidad,
                    inc.fecha_ingreso AS fecha_ingreso,
                    IF(IFNULL(ep.nrodoc,false),1,0)AS tieneEpicrisis
                FROM
                    incli inc LEFT OUTER JOIN
                    IEH i  ON (inc.cod_centro = i.cod_centro
                        AND inc.tipodoc = i.tipodoc
                        AND inc.nrodoc = i.nrodoc
                        AND inc.fecha_ingreso = i.fecha_ingreso) LEFT JOIN
                    tipo_documento td ON (inc.tipodoc = td.id) LEFT JOIN
                    fichaspacientes fp ON (inc.tipodoc = fp.tipodoc AND inc.nrodoc = fp.nrodoc) LEFT JOIN
                    insecfis sfis ON(inc.cod_secfis = sfis.cod_secfis) LEFT JOIN
                    diaglocal dg ON(inc.cod_diagno = dg.id_diagno) LEFT JOIN
                    especialidades e ON(e.cod_especi = i.cod_especi) LEFT JOIN
                    epicrisis ep  ON(inc.fecha_ingreso = ep.fecha_ingreso  AND inc.tipodoc = ep.tipodoc AND inc.nrodoc = ep.nrodoc)
                WHERE
                    inc.tipodoc<>0 AND
                    inc.nrodoc<>0
                    ".$where." ".$sort;

        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->DB_epicrisis->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $internado = $this->DB_epicrisis->fetchRow($query);
            $internacion = array();
            $internacion['cama'] = $internado['cama'];
            $internacion['tipodoc'] = $internado['tipodoc'];
            $internacion['nrodoc'] = $internado['nrodoc'];
            $internacion['paciente'] = Utils::sqlStringToPHP($internado['paciente']);
            /*$internacion['edad'] = $internado['edad']." años";*/
            $internacion['sexo'] = $internado['sexo'];
            $internacion['sector'] = Utils::sqlStringToPHP($internado['sector']);
            $internacion['diagnostico'] = Utils::sqlStringToPHP($internado['diagnostico']);
            $internacion['especialidad'] = Utils::sqlStringToPHP($internado['especialidad']);
            $internacion['fecha_ingreso'] = $internado['fecha_ingreso'];
            $internacion['tiene_epicrisis'] = $internado['tieneEpicrisis'];
            $ret[] = $internacion;
        }

        return $ret;
    }
    
    /**
     * Devuelve la cantidad de internados en el momento
     * @param int $centro
     * @param int $filters
     * @throws Exception
     * @return int 
     */
    private function getCantidadInternados($centro, $filters)
    {

        $where = " AND i.cod_centro = '".$centro."' ";

        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=$filters['rules'][$i]['field']." REGEXP '".$filters['rules'][$i]['data']."' ";
            }
        }

        $query="SELECT
                    count(*) AS cantidad
                FROM
                    incli inc LEFT OUTER JOIN 
                    IEH i  ON (inc.cod_centro = i.cod_centro
                        AND inc.tipodoc = i.tipodoc
                        AND inc.nrodoc = i.nrodoc
                        AND inc.fecha_ingreso = i.fecha_ingreso) LEFT JOIN
                    tipo_documento td ON (inc.tipodoc = td.id) LEFT JOIN
                    fichaspacientes fp ON (inc.tipodoc = fp.tipodoc AND inc.nrodoc = fp.nrodoc) LEFT JOIN
                    insecfis sfis ON(inc.cod_secfis = sfis.cod_secfis) LEFT JOIN
                    diaglocal dg ON(inc.cod_diagno = dg.id_diagno) LEFT JOIN
                    especialidades e ON(e.cod_especi = i.cod_especi)
                WHERE
                    inc.tipodoc<>0 AND
                    inc.nrodoc<>0".$where.";";
                    

        $this->DB_epicrisis->ejecutarQuery($query);
        $result = $this->DB_epicrisis->fetchRow();
        $cantidad = Utils::sqlIntToPHP($result['cantidad']);
        
        return $cantidad;
    }

    public function getInternadosJson($centro, $page, $rows, $filters, $sidx, $sord)
    {
        $response = new stdClass();
        $this->DB_epicrisis->conectar();
        $internados = $this->getInternados($centro, $page, $rows, $filters, $sidx, $sord);
        $response->page = $page;
        $response->total = ceil($this->getCantidadInternados($centro, $filters) / $rows);
        $response->records = $this->getCantidadInternados($centro, $filters);
        $this->DB_epicrisis->desconectar();

        for ($i=0; $i < count($internados); $i++)
        {
            $internado = $internados[$i];
            //id de fila
            $response->rows[$i]['cama'] = $internado['cama'];
            $row = array();
            $row[] = $internado['cama'];
            $row[] = $internado['tipodoc'];
            $row[] = $internado['nrodoc'];
            $row[] = $internado['paciente'];
            /*$row[] = $internado['edad'];*/
            $row[] = $internado['sexo'];
            $row[] = $internado['sector'];
            $row[] = $internado['diagnostico'];
            $row[] = $internado['especialidad'];
            $row[] = $internado['fecha_ingreso'];
            $row[] = $internado['tiene_epicrisis'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Cama']= 'Cama';
        $response->userdata['TipoDoc']= 'TipoDoc';
        $response->userdata['Nrodoc']= 'Nrodoc';
        $response->userdata['Paciente']= 'Paciente';
        /*$response->userdata['Edad']= 'Edad';*/
        $response->userdata['Sexo']= 'Sexo';
        $response->userdata['Sector']= 'Sector';
        $response->userdata['Diagnostico']= 'Diagnostico';
        $response->userdata['Especialidad']= 'Especialidad';
        $response->userdata['Fecha Ingreso']= 'Fecha Ingreso';
        $response->userdata['Tiene Epicrisis']= 'Tiene Epicrisis';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }
    
    public function getTodasEpicrisis($mes ,$anio ,$page, $rows, $filters, $sidx, $sord)
    {
        
        $where="AND MONTH(e.fecha_ingreso) = ".Utils::phpIntToSQL($mes)." 
                AND YEAR(e.fecha_ingreso) = ".Utils::phpIntToSQL($anio)." ";

        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=$filters['rules'][$i]['field']." REGEXP '".$filters['rules'][$i]['data']."' ";
            }
        }

        $sort = "ORDER BY ".$sidx." ".$sord;

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    e.id AS id,
                    e.fecha_ingreso AS fecha_ingreso,
                    td.nombre_corto AS tipodoc,
                    e.nrodoc AS nrodoc,
                    fp.nombre AS paciente,
                    fp.sexo AS sexo,
                    dgi.diag_local AS diag_ingreso,
                    IFNULL(dgs.diag_local, 'Sin Cargar') AS diag_egreso,
                    IFNULL(ee.fecha_creacion, 'Sin Cargar') AS fecha_salida
                FROM
                    epicrisis e LEFT JOIN
                    tipo_documento td ON (e.tipodoc=td.id) LEFT JOIN
                    fichaspacientes fp ON(e.tipodoc = fp.tipodoc AND e.nrodoc = fp.nrodoc) LEFT JOIN
                    (SELECT 
                            cod_diagno as cod_diagno,
                            MAX(diag_local) as diag_local 
                        FROM 
                            diaglocal
                        GROUP BY cod_diagno) dgi ON(e.cod_diagno_ingreso = dgi.cod_diagno)LEFT JOIN
                    epicrisis_egresos ee ON(e.id=ee.idepicrisis AND ee.habilitado=1) LEFT JOIN
                    diaglocal dgs ON(ee.diagno_egreso=dgs.id_diagno)
                WHERE
                    e.habilitado=1  ".$where." ".$sort;

        $query = $query . " LIMIT ".$rows." OFFSET ".$offset.";";

        $this->DB_epicrisis->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->DB_epicrisis->querySize; $i++)
        {
            $epicrisis_item = $this->DB_epicrisis->fetchRow($query);
            $epicrisis = array();
            $epicrisis['id'] = $epicrisis_item['id'];
            $epicrisis['fecha_ingreso'] = $epicrisis_item['fecha_ingreso'];
            $epicrisis['tipodoc'] = $epicrisis_item['tipodoc'];
            $epicrisis['nrodoc'] = $epicrisis_item['nrodoc'];
            $epicrisis['paciente'] = Utils::phpStringToHTML($epicrisis_item['paciente']);
            $epicrisis['sexo'] = $epicrisis_item['sexo'];
            $epicrisis['diag_ingreso'] = Utils::phpStringToHTML($epicrisis_item['diag_ingreso']);
            $epicrisis['diag_egreso'] = Utils::phpStringToHTML($epicrisis_item['diag_egreso']);
            $epicrisis['fecha_salida'] = $epicrisis_item['fecha_salida'];
            $ret[] = $epicrisis;
        }

        return $ret;
    }

    private function getCantidadEpicrisis($mes, $anio, $filters)
    {

        $where="AND MONTH(e.fecha_ingreso) = ".Utils::phpIntToSQL($mes)." 
                AND YEAR(e.fecha_ingreso) = ".Utils::phpIntToSQL($anio)." ";

        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=$filters['rules'][$i]['field']." REGEXP '".$filters['rules'][$i]['data']."' ";
            }
        }

        $query2="SELECT
                    count(*) AS cantidad
                FROM
                    epicrisis e LEFT JOIN
                    tipo_documento td ON (e.tipodoc=td.id) LEFT JOIN
                    fichaspacientes fp ON(e.tipodoc = fp.tipodoc AND e.nrodoc = fp.nrodoc) LEFT JOIN
                    (SELECT 
                            cod_diagno as cod_diagno,
                            MAX(diag_local) as diag_local 
                        FROM 
                            diaglocal
                        GROUP BY cod_diagno) dgi ON(e.cod_diagno_ingreso = dgi.cod_diagno)LEFT JOIN
                    epicrisis_egresos ee ON(e.id=ee.idepicrisis AND ee.habilitado=1) LEFT JOIN
                    diaglocal dgs ON(ee.diagno_egreso=dgs.id_diagno)
                WHERE 
                    e.habilitado=1 ".$where.";";
                    

        $this->DB_epicrisis->ejecutarQuery($query2);
        $result = $this->DB_epicrisis->fetchRow();
        $cantidad = Utils::sqlIntToPHP($result['cantidad']);
        
        return $cantidad;
    }

    public function getTodasEpicrisisJson($mes, $anio, $page, $rows, $filters, $sidx, $sord)
    {
        $response = new stdClass();
        $this->DB_epicrisis->conectar();
        $lista_epicrisis = $this->getTodasEpicrisis($mes, $anio, $page, $rows, $filters, $sidx, $sord);
        $response->page = $page;
        $response->total = ceil($this->getCantidadEpicrisis($mes, $anio, $filters) / $rows);
        $response->records = $this->getCantidadEpicrisis($mes, $anio, $filters);
        $this->DB_epicrisis->desconectar();

        for ($i=0; $i < count($lista_epicrisis); $i++)
        {
            $epicrisis = $lista_epicrisis[$i];

            $response->rows[$i]['id'] = $epicrisis['id'];

            $row = array();
            $row[] = $epicrisis['id'];
            $row[] = $epicrisis['fecha_ingreso'];
            $row[] = $epicrisis['tipodoc'];
            $row[] = $epicrisis['nrodoc'];
            $row[] = $epicrisis['paciente'];
            $row[] = $epicrisis['sexo'];
            $row[] = $epicrisis['diag_ingreso'];
            $row[] = $epicrisis['diag_egreso'];
            $row[] = $epicrisis['fecha_salida'];
            $row[] = '';

            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['id']= 'id';
        $response->userdata['fecha_ingreso']= 'fecha_ingreso';
        $response->userdata['tipodoc']= 'tipodoc';
        $response->userdata['nrodoc']= 'nrodoc';
        $response->userdata['paciente']= 'paciente';
        $response->userdata['sexo']= 'sexo';
        $response->userdata['diag_ingreso']= 'diag_ingreso';
        $response->userdata['diag_egreso']= 'diag_egreso';
        $response->userdata['fecha_salida']= 'fecha_salida';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    public function tieneAntecedente($idEpicrisis)
    {
        $query="SELECT 
                    count(*) AS cantidad
                FROM
                    epicrisis_antecedentes
                WHERE
                    idepicrisis=".Utils::sqlIntToPHP($idEpicrisis).";";
        try 
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow();

        $this->DB_epicrisis->desconectar();

        $cantidad = Utils::sqlIntToPHP($result['cantidad']);
        
        return $cantidad>0;
    }

    public function tieneObservacionDeTipo($idEpicrisis,$idTipoObservacion)
    {
        $query="SELECT
                    count(*) AS cantidad
                FROM
                    epicrisis_observaciones
                WHERE
                    idepicrisis=".Utils::sqlIntToPHP($idEpicrisis)." AND 
                    idtipo_observacion=".$idTipoObservacion." AND 
                    habilitado=1;";
        try 
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow();

        $this->DB_epicrisis->desconectar();

        $cantidad = Utils::sqlIntToPHP($result['cantidad']);
        
        return $cantidad>0;
    }

    public function puedeDarDestino($idEpicrisis)
    {
        /*  Tiene que tener:
            - Antecedente
            - Evolucion Clinica
            - Tratamiento al alta
            - Tratamiento Medico
        */
        $data = new stdClass();

        $data->result = true;

        $data->show = false;

        $data->message = "Debe llenar al menos un campo de los siguientes items para ingresar el egreso.<br>";

        try
        {
            $tieneAntecedente = $this->tieneAntecedente($idEpicrisis);
            $tieneEvolucionClinica = $this->tieneObservacionDeTipo($idEpicrisis,2);//Evolucion Clinica
            $tieneTratamientoMedico = $this->tieneObservacionDeTipo($idEpicrisis,3);//Tratamiento Medico
            $tieneTratamientoAlAlta = $this->tieneObservacionDeTipo($idEpicrisis,6);//Tratamiento al Alta
        }
        catch (Exception $e)
        {
            throw new Exception("Ocurrio un error al consultar los items obligatorios a completar", 201230);
            exit();
        }

        if(!$tieneAntecedente)
        {
            $data->message.= " - Antecedente<br>";
            $data->result = false;
        }

        if(!$tieneEvolucionClinica)
        {
            $data->message.= " - Evolucion Clinica<br>";
            $data->result = false;
        }

        if(!$tieneTratamientoMedico)
        {
            $data->message.= " - Tratamiento Medico<br>";
            $data->result = false;
        }

        if(!$tieneTratamientoAlAlta)
        {
            $data->message.= " - Tratamiento al Alta<br>";
            $data->result = false;
        }

        return $data;
    }


    public function puedeEditar($idEpicrisis)
    {
        $response = new stdClass();

        $tieneTiempo = false;

        $message = "";

        $query="SELECT
                    fecha_creacion
                FROM
                    epicrisis_egresos
                WHERE
                    idepicrisis=".$idEpicrisis." AND
                    habilitado=1;";

        try 
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow();

        $this->DB_epicrisis->desconectar();

        $fecha = $result['fecha_creacion'];

        if($fecha!=NULL)
        {
            #CALCULO DE DOS HORAS PARA QUE EL PROFESIONAL ELIMNAR LA SALIDA#

            // Fecha y hora del usuario al momento de apretar en eliminar
            $hoy = getdate();
            $datetime_ahora = new DateTime();
            $datetime_ahora->setTimestamp($hoy['0']);

            // Fecha y hora de la salida al momento que fue grabado en la base de datos.
            $datetime_salida =  new DateTime();
            $datetime_salida->setTimestamp(Utils::sqlDateTimeToPHPTimestamp($fecha));

            // Calculo la diferencia entre fecha y hora que hay desde la primera salida y el momento que quiere eliminarla
            $interval = $datetime_ahora->diff($datetime_salida);

            if($interval->y == 0 AND $interval->m == 0 AND $interval->d == 0 AND $interval->h < 2)
            {
                $tieneTiempo = true;
            }
            else
            {
                $message = "Tiene hasta dos horas despues de haber ingresado el egreso para editar el atributo";
            }
        }
        else
        {
            $tieneTiempo = true;
        }

        $response->tieneTiempo = $tieneTiempo;

        $response->message = $message;

        return $response;
    }

    public function obtenerObservacion($idObservacion)
    {
        $query="SELECT
                    id,
                    idepicrisis,
                    idtipo_observacion,
                    detalle,
                    fecha_ingreso,
                    idusuario,
                    idpadre,
                    fecha_edicion,
                    habilitado
                FROM
                    epicrisis_observaciones
                WHERE
                    id=".$idObservacion.";";
        try 
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->DB_epicrisis->fetchRow();

        $this->DB_epicrisis->desconectar();

        $obs = array();

        $obs['id'] = $result['id'];
        $obs['idepicrisis'] = $result['idepicrisis'];
        $obs['idtipo_observacion'] = $result['idtipo_observacion'];
        $obs['detalle'] = $result['detalle'];
        $obs['fecha_ingreso'] = $result['fecha_ingreso'];
        $obs['idusuario'] = $result['idusuario'];
        $obs['idpadre'] = $result['idpadre'];
        $obs['fecha_edicion'] = $result['fecha_edicion'];
        $obs['habilitado'] = $result['habilitado'];

        return $obs;
    }

    public function eliminarObservacion($idObservacion)
    {
        $query="UPDATE 
                    `epicrisis_observaciones`
                SET
                    `habilitado`='0'
                WHERE
                    `id`=".$idObservacion.";";
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            throw new Exception("No se pudo eliminar la observacion", 201230);
        }
        $this->DB_epicrisis->desconectar();

        return true;
    }

    public function editarObservacion($idObservacion,$nuevoTexto)
    {
        $response = new stdClass();

        $response->result = true;

        $obsAnt = $this->obtenerObservacion($idObservacion);

        try
        {
            $this->eliminarObservacion($idObservacion);    
        }
        catch(Exception $e)
        {
            $response->message = "Ocurrio un error al actualizar el detalle de la observacion";
            $response->result = false;
        }

        $query="INSERT INTO `epicrisis_observaciones`
                    (
                    `idepicrisis`,
                    `idtipo_observacion`,
                    `detalle`,
                    `fecha_ingreso`,
                    `idusuario`,
                    `idpadre`,
                    `fecha_edicion`,
                    `habilitado`
                    )
                VALUES
                    (  
                    ".Utils::phpIntToSQL($obsAnt['idepicrisis']).",
                    ".Utils::phpIntToSQL($obsAnt['idtipo_observacion']).",
                    ".Utils::phpStringToSQL($nuevoTexto).",
                    '".$obsAnt['fecha_ingreso']."',
                    ".Utils::phpIntToSQL($obsAnt['idusuario']).",
                    ".Utils::phpIntToSQL($obsAnt['id']).",
                    '',
                    '1'
                    );";
        
        try
        {
            $this->DB_epicrisis->conectar();
            $this->DB_epicrisis->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->DB_epicrisis->desconectar();
            $response->message = "Ocurrio un error al insertar la observacion nueva";
            $response->result = false;
        }

        $this->DB_epicrisis->desconectar();

        return $response;
    }
}