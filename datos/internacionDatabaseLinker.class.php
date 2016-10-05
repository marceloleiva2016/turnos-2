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
            $query="INSERT INTO
                        internacion(
                            `tipodoc`,
                            `nrodoc`,
                            `idatencion_predecesora`,
                            `motivo_ingreso`,
                            `iddiagnostico_ingreso`,
                            `fecha_creacion`,
                            `idusuario`,
                            `habilitado`)
                    VALUES (
                            $tipodoc,
                            $nrodoc,
                            $idatencionPredecesora,
                            ".Utils::phpStringToSQL($motivoIngreso).",
                            $idDiagnosticoIngreso,
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
}