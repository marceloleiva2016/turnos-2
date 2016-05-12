<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once neg_formulario.'demanda/demanda.class.php';
include_once neg_formulario.'demanda/demandaObservacion.class.php';
include_once neg_formulario.'demanda/demandaItemObservacion.class.php';
include_once neg_formulario.'demanda/demandaEgreso.class.php';

class DemandaDatabaseLinker
{
    private $dbTurnos;

//-------------------------------------Constructor--------------------------------------------

    function DemandaDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        if (!isset($_SESSION)) 
        {
            session_start();
        }
    }

    function getId($idAtencion)
    {
        $query="SELECT
                    id
                FROM
                    form_demanda
                WHERE
                    idatencion = ".Utils::phpIntToSQL($idAtencion).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function crear($idAtencion, $tipodoc, $nrodoc, $iduser)
    {
        $query="INSERT INTO
                    form_demanda(
                            `tipodoc`,
                            `nrodoc`,
                            `idatencion`,
                            `fecha_creacion`,
                            `idusuario`,
                            `habilitado`)
                VALUES (
                        ".Utils::phpIntToSQL($tipodoc).",
                        ".Utils::phpIntToSQL($nrodoc).",
                        ".Utils::phpIntToSQL($idAtencion).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
                        '1'
                        );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $id = $this->dbTurnos->ultimoIdInsertado();

        $this->dbTurnos->desconectar();

        return $id;
    }

    /**
    * @return Demanda
    * devuelve un objeto demanda con todo cargado
    */
    function obtenerFormulario($idAtencion, $iduser)
    {
        $id = $this->getId($idAtencion);

        $dbAtencion = new AtencionDatabaseLinker();
        $dbProfesional = new ProfesionalDatabaseLinker();
        $dbPaciente = new PacienteDatabaseLinker();
        
        $datosAtencion = $dbAtencion->obtenerVariablesAtencion($idAtencion);
        $profesional = $dbProfesional->getProfesional($datosAtencion['idprofesional']);
        $paciente = $dbPaciente->getDatosPacientePorNumero($datosAtencion['tipodoc'], $datosAtencion['nrodoc']);

        if(is_null($id))
        {
            $id = $this->crear($idAtencion, $datosAtencion['tipodoc'], $datosAtencion['nrodoc'], $iduser);
        }

        $demanda = new Demanda();
        $demanda->setId($id);
        $demanda->setIdAtencion($idAtencion);
        $demanda->setProfesional($profesional);
        $demanda->setPaciente($paciente);
        $this->cargarObservaciones($demanda);
        $this->cargarEgreso($demanda);

        return $demanda;
    }

    public function traerTiposObservaciones($idDemanda)
    {
        $query="SELECT
                    dto.id,
                    dto.detalle
                FROM
                    form_demanda_tipo_observacion dto
                WHERE
                    dto.id IN(SELECT
                                DISTINCT(do.idtipo_observacion) AS id
                            FROM
                                form_demanda_observaciones do
                            WHERE
                                do.iddemanda=".Utils::phpIntToSQL($idDemanda)." AND
                                do.habilitado=1);";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $observaciones = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $observacion = new DemandaObservacion();
            $observacion->setTipo($result['id']);
            $observacion->setDetalle($result['detalle']);
            $observaciones[] = $observacion;
        }

        $this->dbTurnos->desconectar();

        return $observaciones;
    }

    public function cargarObservaciones(Demanda $dem)
    {
        $observaciones = $this->traerTiposObservaciones($dem->getId());
    
        for ($i=0; $i < count($observaciones); $i++)
        {
            $query="SELECT
                        do.id AS id,
                        do.detalle AS detalle,
                        do.fecha_ingreso AS fecha,
                        u.nombre AS usuario
                    FROM 
                        form_demanda_observaciones do JOIN
                        usuario u ON(do.iduser=u.idusuario)
                    WHERE
                        do.iddemanda=".Utils::phpIntToSQL($dem->getId())." AND
                        do.idtipo_observacion=".Utils::phpIntToSQL($observaciones[$i]->getTipo())." AND
                        do.habilitado=1;";



            $this->dbTurnos->conectar();

            try
            {
                $this->dbTurnos->ejecutarQuery($query);
            }
            catch (Exception $e)
            {
                $this->dbTurnos->desconectar();
                throw new Exception("No se pudo traer items de la observacion ".$observaciones[$i]->getDetalle()." para la demanda con id = ".$dem->getId().", Por favor comuniquese con informatica con esa informacion de error. Gracias!", 201230);
            }

            for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
            {
                $result = $this->dbTurnos->fetchRow($query);
                $fecha = Utils::phpTimestampToHTMLDateTime(Utils::sqlDateTimeToPHPTimestamp($result['fecha']));
                $observaciones[$i]->agregarItem($result['id'], $fecha, $result['usuario'], $result['detalle']);
            }

            $this->dbTurnos->desconectar();
        }
    
        $dem->agregarObservaciones($observaciones);

        $this->dbTurnos->desconectar();
    }

    function cargarEgreso(Demanda $dem)
    {
        $variablesEgreso = $this->getEgreso($dem->getId());

        $egreso = new Egreso();
        $egreso->setId($variablesEgreso['id']);
        $egreso->setDiagnostico($variablesEgreso['diagnostico']);
        $egreso->setTipoEgreso($variablesEgreso['tipo_egreso']);
        $egreso->setFechaCreacion($variablesEgreso['fecha_creacion']);
        $egreso->setUsuario($variablesEgreso['usuario']);

        $dem->setEgreso($egreso);
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

        $query="INSERT INTO form_demanda_observaciones
                    (
                    `iddemanda`,
                    `idtipo_observacion`,
                    `detalle`,
                    `fecha_ingreso`,
                    `iduser`,
                    `habilitado`
                    )
                    VALUES
                    (
                    ".Utils::phpIntToSQL($obsAnt['iddemanda']).",
                    ".Utils::phpIntToSQL($obsAnt['idtipo_observacion']).",
                    ".Utils::phpStringToSQL($nuevoTexto).",
                    now(),
                    ".Utils::phpIntToSQL($obsAnt['iduser']).",
                    '1'
                    );";
        
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $response->message = "Ocurrio un error al insertar la observacion nueva";
            $response->result = false;
        }

        $this->dbTurnos->desconectar();

        return $response;
    }

    public function obtenerObservacion($idObservacion)
    {
        $query="SELECT
                    id,
                    iddemanda,
                    idtipo_observacion,
                    detalle,
                    fecha_ingreso,
                    iduser,
                    habilitado
                FROM
                    form_demanda_observaciones
                WHERE
                    id=".$idObservacion.";";
        try 
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        $obs = array();

        $obs['id'] = $result['id'];
        $obs['iddemanda'] = $result['iddemanda'];
        $obs['idtipo_observacion'] = $result['idtipo_observacion'];
        $obs['detalle'] = $result['detalle'];
        $obs['fecha_ingreso'] = $result['fecha_ingreso'];
        $obs['iduser'] = $result['iduser'];
        $obs['habilitado'] = $result['habilitado'];

        return $obs;
    }

    public function eliminarObservacion($idObservacion)
    {
        $query="UPDATE 
                    form_demanda_observaciones
                SET
                    habilitado='0'
                WHERE
                    id=".$idObservacion.";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo eliminar la observacion", 201230);
        }
        $this->dbTurnos->desconectar();

        return true;
    }

    public function nombreTipoObservacion($idTipoObs)
    {
        $query="SELECT
                    detalle
                FROM
                    form_demanda_tipo_observacion
                WHERE 
                    id=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones selecionadas para la epìcrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        return $result['detalle'];
    }

    public function obtenerCantidadObservacionesDeTipo($iddemanda, $idTipoObs)
    {
        $query="SELECT 
                    count(*) AS cantidad
                FROM 
                    form_demanda_observaciones do JOIN
                    usuario u ON(do.iduser=u.idusuario)
                WHERE
                    iddemanda=".Utils::phpIntToSQL($iddemanda)." AND
                    idtipo_observacion=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return Utils::sqlIntToPHP($result['cantidad']);
    }

    public function insertarObservacion($iddemanda, $tipoObs, $texto, $iduser)
    {
        $query="INSERT INTO 
                    form_demanda_observaciones
                    (
                        `iddemanda`,
                        `idtipo_observacion`,
                        `detalle`,
                        `fecha_ingreso`,
                        `iduser`,
                        `habilitado`
                    )
                VALUES
                    (
                        ".Utils::phpIntToSQL($iddemanda).",
                        ".Utils::phpIntToSQL($tipoObs).",
                        ".Utils::phpStringToSQL($texto).",
                        now(),
                        ".$iduser.",
                        '1'
                    );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
        }

        $this->dbTurnos->desconectar();
        return true;
    }


    function getTiposEgresos()
    {
        $query="SELECT
                    id,
                    detalle
                FROM
                    form_demanda_tipo_egreso
                WHERE
                    habilitado=1;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $destinos  = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $destinos[] = $result;
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $destinos;
    }

    function insertarEgreso($idDemanda, $idTipoEgreso, $idDiagnostico, $iduser)
    {
        $query="INSERT INTO
                    form_demanda_egreso
                        (`iddemanda`,
                        `idtipo_egreso_demanda`,
                        `iddiagnostico`,
                        `fecha_creacion`,
                        `iduser`,
                        `habilitado`)
                VALUES (
                        ".Utils::phpIntToSQL($idDemanda).",
                        ".Utils::phpIntToSQL($idTipoEgreso).",
                        ".Utils::phpIntToSQL($idDiagnostico).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
                        '1'
                        );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
        }

        try
        {
            $idAtencion = $this->getIdAtencion($idDemanda);

            $dbAtencion = new AtencionDatabaseLinker();

            $dbAtencion->actualizarEstadoDeSuTurno($idAtencion, 3, $iduser);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Fallo en actualizar el turno a atendido", 201230);       
        }

        $this->dbTurnos->desconectar();

        return true;
    }

    function getEgreso($idDemanda)
    {
        $query="SELECT
                    de.id as id,
                    dte.detalle as tipo_egreso,
                    d.descripcion as diagnostico,
                    de.fecha_creacion as fecha_creacion,
                    u.nombre as usuario
                FROM
                    form_demanda_egreso de LEFT JOIN 
                    usuario u ON(u.idusuario=de.iduser) LEFT JOIN
                    diagnosticos_diagnostico d ON(de.iddiagnostico=d.id) LEFT JOIN
                    form_demanda_tipo_egreso dte ON(dte.id= de.idtipo_egreso_demanda)
                WHERE
                    de.habilitado=true AND
                    de.iddemanda = ".Utils::phpIntToSQL($idDemanda).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;
    }

    function puedeDarEgreso($idDemanda)
    {   
        $std = new StdClass();
        $std->puede = true;

        $cantidad = $this->cantidadObservaciones($idDemanda);

        if($cantidad==0)
        {
            $std->puede = false;
            $std->message = "No se cargo ninguna observacion";
        }

        $egreso=$this->getEgreso($idDemanda);

        if($egreso['id']!=null)
        {
            $std->puede = false;
            $std->message = "Ya existe un egreso cargado";
        }

        return $std;
    }

    function cantidadObservaciones($idDemanda)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM
                    form_demanda_observaciones
                WHERE 
                    iddemanda=".Utils::phpIntToSQL($idDemanda).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['cantidad'];
    }

    function getIdAtencion($idDemanda)
    {
        $query="SELECT
                    idatencion
                FROM
                    form_demanda
                WHERE
                    id=$idDemanda";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idatencion'];
    }
}
