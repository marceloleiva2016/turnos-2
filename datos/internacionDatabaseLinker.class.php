<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'camaDatabaseLinker.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'diagnosticoDatabaseLinker.class.php';
include_once datos.'sectorDatabaseLinker.class.php';
include_once datos.'obraSocialDatabaseLinker.class.php';
include_once negocio.'internacion.class.php';

class InternacionDatabaseLinker
{
    var $dbTurnos;
    var $dbCama;
    var $dbPaciente;
    var $dbDiagnostico;
    var $dbObraSocial;

    function InternacionDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        $this->dbCama = new CamaDatabaseLinker();
        $this->dbPaciente = new PacienteDatabaseLinker();
        $this->dbDiagnostico = new DiagnosticoDatabaseLinker();
        $this->dbSector = new SectorDatabaseLinker();
        $this->dbObraSocial = new ObraSocialDatabaseLinker();
    }

    function salidaAInternacion($idInternacion, $idusuario)
    {
        $idCama = $this->dbCama->getIdCamaEnInternacion($idInternacion);

        $this->dbCama->salidaInternacionCama($idCama, $idusuario);
    }

    function obtenerVariablesInternacion($idInternacion)
    {
        /*van a ser variables basicas para la lectura del formulario principal*/

        $query="SELECT
                    t.tipodoc as tipodoc,
                    t.nrodoc as nrodoc,
                    null as idsubespecialidad,
                    null as idprofesional,
                    2 as idtipo_atencion
                FROM
                    internacion t LEFT JOIN
                    cama_internacion ci on (t.id = ci.idinternacion) LEFT JOIN 
                    cama c on (ci.idcama=c.id) LEFT JOIN
                    sector s ON(c.idsector=s.id)
                WHERE
                    t.id=$idInternacion;";
                    
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e) 
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando las variables de la internacion", 1);              
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;
    }

    function crearInternacion($tipodoc, $nrodoc, $idatencionPredecesora, $motivoIngreso, $idDiagnosticoIngreso, $idCama, $idusuario)
    {
        $response = new stdClass();

        if(!$this->dbCama->existeInternadoEnCama($idCama))//Me fijo si la cama en la que lo va a internar esta vicia por si a caso.
        {
            if(!$this->estaInternadoElPaciente($tipodoc, $nrodoc))
            {                
                $obraSocial = $this->dbObraSocial->getObraSocialPaciente($tipodoc, $nrodoc);

                $query="INSERT INTO
                            internacion(
                                `tipodoc`,
                                `nrodoc`,
                                `idatencion_predecesora`,
                                `motivo_ingreso`,
                                `iddiagnostico_ingreso`,
                                `idobra_social`,
                                `fecha_creacion`,
                                `idusuario`,
                                `habilitado`)
                        VALUES (
                                $tipodoc,
                                $nrodoc,
                                $idatencionPredecesora,
                                ".Utils::phpStringToSQL($motivoIngreso).",
                                $idDiagnosticoIngreso,
                                ".$obraSocial['id'].",
                                now(),
                                $idusuario,
                                true
                                );";
                try
                {
                    $this->dbTurnos->conectar();
                    $this->dbTurnos->ejecutarAccion($query);    
                    $response->message = "Paciente Internado";
                    $response->ret = true;
                }
                catch (Exception $e)
                {
                    $this->dbTurnos->desconectar();
                    $response->message = "Ocurrio un error al crear la internacion!";
                    $response->ret = false;
                }

                $idinternacion = $this->dbTurnos->ultimoIdInsertado();

                $this->dbCama->internarnarEnCama($idCama, $idinternacion, $idusuario);

                $this->insertarEnLog($idinternacion, $idCama, $idusuario);
                
                $this->dbTurnos->desconectar();
            }
            
        }
        else
        {
            $response->message = "No se puede internar en paciente debido a que ya existe un internado en la cama seleccionada!";
            $response->ret = false;
        }

        return $response;
    }

    function insertarEnLog($idinternacion, $idcama, $idusuario)
    {
        $query="INSERT INTO
                    internacion_log_cama (
                        `idinternacion`,
                        `idcama`,
                        `idusuario`,
                        `fecha_creacion`,
                        `habilitado`)
                VALUES (
                        $idinternacion,
                        $idcama,
                        $idusuario,
                        now(),
                        true );";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
        }
        
        $this->dbTurnos->desconectar();

        return true;
    }

    function paseCama($idInternacion, $idCamaPrevia, $idCamaNueva, $idusuario)
    {
        try
        {
            //Bajo la internacion nueva.
            $this->dbCama->internarnarEnCama($idCamaPrevia, 0, $idusuario);

            //Doy de alta en la nueva cama.
            $this->dbCama->internarnarEnCama($idCamaNueva, $idInternacion, $idusuario);    
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }

    function getInternado($idInternacion)
    {
        $query="SELECT
                    id,
                    tipodoc,
                    nrodoc,
                    idatencion_predecesora,
                    motivo_ingreso,
                    iddiagnostico_ingreso,
                    idobra_social,
                    fecha_creacion
                FROM
                    internacion
                WHERE
                    habilitado=true AND
                    id=$idInternacion;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e) 
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando las variables del turno", 1);              
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        $paciente = $this->dbPaciente->getDatosPacientePorNumero($result['tipodoc'], $result['nrodoc']);
        $obraSocial = $this->dbObraSocial->getObraSocial($result['idobra_social']);
        $diagnostico = $this->dbDiagnostico->getDiagnosticoPorId($result['iddiagnostico_ingreso']);

        $internado = new Internacion();
        $internado->setId($result['id']);
        $internado->setTipo_atencion_predecesora($result['idatencion_predecesora']);
        $internado->setMotivo_ingreso($result['motivo_ingreso']);
        $internado->setFecha_creacion($result['fecha_creacion']);
        $internado->setPaciente($paciente);
        $internado->setObraSocial($obraSocial);
        $internado->setDiagnostico($diagnostico);

        return $internado;
    }

    function getInternadosTodos($anio, $mes ,$page, $rows, $filters)
    {

        $where = "";
        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=" ".$filters['rules'][$i]['field']." like '".$filters['rules'][$i]['data']."%'";
            }
        }

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    i.id,
                    td.detalle_corto as tipodoc,
                    i.nrodoc,
                    concat(p.nombre,' ', p.apellido) as nombre,
                    i.idatencion_predecesora,
                    i.motivo_ingreso,
                    d.descripcion as diagnostico,
                    o.detalle as osoc,
                    i.fecha_creacion,
                    s.detalle as sector
                FROM
                    internacion i LEFT JOIN
                    paciente p ON(i.tipodoc=p.tipodoc AND i.nrodoc=p.nrodoc) LEFT JOIN
                    (SELECT 
                            il.idinternacion,
                            (select idcama from internacion_log_cama where fecha_creacion=max(il.fecha_creacion) limit 1) as idcama,
                            max(il.fecha_creacion) as fecha_creacion
                    FROM
                        internacion_log_cama il
                    group by
                        idinternacion) ilc ON(ilc.idinternacion=i.id) LEFT JOIN
                    cama c ON(ilc.idcama=c.id) LEFT JOIN
                    sector s ON(c.idsector=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    diagnosticos_diagnostico d ON(i.iddiagnostico_ingreso=d.id) LEFT JOIN
                    obra_social o ON(o.id=i.idobra_social) LEFT JOIN
                    tipo_documento td on(i.tipodoc=td.id)
                WHERE
                    i.habilitado=true AND
                    year(i.fecha_creacion)=2016 AND
                    month(i.fecha_creacion)=10
                    ".$where."
                ORDER BY i.fecha_creacion ASC 
                LIMIT $rows OFFSET $offset;";

        try
        {
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e) 
        {
            throw new Exception("Error consultando las variables de la internacion", 1);              
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);

            $int = array();
            $int['id'] = $result['id'];
            $int['tipodoc'] = $result['tipodoc'];
            $int['nrodoc'] = $result['nrodoc'];
            $int['nombre'] = $result['nombre'];
            $int['motivo_ingreso'] = $result['motivo_ingreso'];
            $int['diagnostico'] = $result['diagnostico'];
            $int['obra_social'] = $result['osoc'];
            $int['sector'] = $result['sector'];
            $int['fecha_creacion'] = Utils::sqlDateTimeToHtmlDateTime($result['fecha_creacion']);

            $ret[] = $int;
        }

        return $ret;
    }

    private function getCantidadInternadosTodos($anio, $mes ,$filters = null)
    {
        $where = "";
        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=" ".$filters['rules'][$i]['field']." like '".$filters['rules'][$i]['data']."%'";
            }
        }

        $query="SELECT
                    count(*) as cantidad
                FROM
                    internacion i LEFT JOIN
                    paciente p ON(i.tipodoc=p.tipodoc AND i.nrodoc=p.nrodoc) LEFT JOIN
                    (SELECT 
                            il.idinternacion,
                            (select idcama from internacion_log_cama where fecha_creacion=max(il.fecha_creacion) limit 1) as idcama,
                            max(il.fecha_creacion) as fecha_creacion
                    FROM
                        internacion_log_cama il
                    group by
                        idinternacion) ilc ON(ilc.idinternacion=i.id) LEFT JOIN
                    cama c ON(ilc.idcama=c.id) LEFT JOIN
                    sector s ON(c.idsector=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    diagnosticos_diagnostico d ON(i.iddiagnostico_ingreso=d.id) LEFT JOIN
                    obra_social o ON(o.id=i.idobra_social)
                WHERE
                    i.habilitado=true AND
                    year(i.fecha_creacion)=$anio AND
                    month(i.fecha_creacion)=$mes
                    ".$where.";";
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];

        return $ret;
    }

    function getInternadosTodosJson($anio, $mes ,$page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $internacion_array = $this->getInternadosTodos($anio, $mes ,$page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadInternadosTodos($anio, $mes ,$filters) / $rows);
        $response->records = $this->getCantidadInternadosTodos($anio, $mes ,$filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($internacion_array) ; $i++) 
        {
            $internacion = $internacion_array[$i];
            //id de fila
            $response->rows[$i]['id'] = $internacion['id']; 

            $row = array();
            $row['nro'] = $internacion['id'];
            $row['tipodoc'] = $internacion['tipodoc'];
            $row['nrodoc'] = $internacion['nrodoc'];
            $row['nombre'] = $internacion['nombre'];
            $row['motivo_ingreso'] = $internacion['motivo_ingreso'];
            $row['diagnostico'] = $internacion['diagnostico'];
            $row['obra_social'] = $internacion['obra_social'];
            $row['sector'] = $internacion['sector'];
            $row['fecha_creacion'] = $internacion['fecha_creacion'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['tipodoc']= 'Tipodoc';
        $response->userdata['nrodoc']= 'Nrodoc';
        $response->userdata['nombre']= 'Nombre';
        $response->userdata['motivo_ingreso']= 'motivo_ingreso';
        $response->userdata['diagnostico']= 'diagnostico';
        $response->userdata['obra_social']= 'obra_social';
        $response->userdata['sector']= 'sector';
        $response->userdata['fecha_creacion']= 'fecha_creacion';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function getInternadosEnSector($idsector)
    {
        $camas = $this->dbCama->getCamasEnSector($idsector);

        $internados = array();

        for ($i=0; $i < count($camas); $i++)
        {
            $internacion = array();
            $internacion['cama'] = $camas[$i];

            $idinternacion = $camas[$i]->getIdInternacion();

            if($idinternacion!='0')
            {
                $internacion['internado'] = $this->getInternado($idinternacion);    
            }
            else
            {
                $internacion['internado'] = null;
            }

            $internados[]=$internacion;
        }

        return $internados;
    }

    function getInternados()
    {
        $sectores = $this->dbSector->getSectores();

        $intPorSector = array();

        for ($c=0; $c < count($sectores); $c++)
        {
            $camas = $this->dbCama->getCamasEnSector($sectores[$c]->getId());

            $internados = array();

            for ($i=0; $i < count($camas); $i++)
            {
                $internacion = array();
                $internacion['cama'] = $camas[$i];

                $idinternacion = $camas[$i]->getIdInternacion();

                if($idinternacion!='0')
                {
                    $internacion['internado'] = $this->getInternado($idinternacion);    
                }
                else
                {
                    $internacion['internado'] = null;
                }

                $internados[] = $internacion;
            }

            $tupla = array('internados' => $internados, 'sector' => $sectores[$c]);
            $intPorSector[] = $tupla;
        }

        return $intPorSector;
    }

    function estaInternadoElPaciente($tipodoc, $nrodoc)
    {
        $query="SELECT
                    *
                FROM
                    cama_internacion ci LEFT JOIN
                    internacion i ON(ci.idinternacion=i.id)
                WHERE
                    i.tipodoc=$tipodoc AND
                    i.nrodoc=$nrodoc;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error si el paciente se encuentra internado!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result!=null;
    }
}