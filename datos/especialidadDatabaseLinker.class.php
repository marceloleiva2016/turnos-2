<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'especialidad.class.php';

class EspecialidadDatabaseLinker
{
    var $dbTurnos;

    function EspecialidadDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getEspecialidades()
    {
        $response = new stdClass();

        $query="SELECT 
                    id,
                    detalle,
                FROM
                    especialidad
                WHERE
                    habilitado=1;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "Especialidades consultadas";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $response->message = "Consultando las especialidades";
            $response->ret = false;
        }

        $especialidades = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $Especialidad = new Especialidad();
            $Especialidad->setId($result['id']);
            $Especialidad->setDetalle($result['detalle']);
            $ret[] = $Especialidad;
        }

        $response->data = $especialidades;

        $this->dbTurnos->desconectar();

        return $response;
    }

    function getEspecialidadesConConsultoriosDeDemandaActivos()
    {
        $query="SELECT 
                    id,
                    detalle
                FROM
                    especialidad
                WHERE
                    habilitado=1 AND
                    id IN (SELECT
                                s.idespecialidad
                            FROM
                                subespecialidad s LEFT JOIN
                                consultorio c ON (c.idsubespecialidad = s.id)
                            WHERE
                                c.habilitado=1 AND
                                c.idtipo_consultorio=1 AND
                                s.habilitado=1);";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando las especialidades con consultorios de guardia", 1);
        }

        $especialidades = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $Especialidad = new Especialidad();
            $Especialidad->setId($result['id']);
            $Especialidad->setDetalle($result['detalle']);
            $especialidades[] = $Especialidad;
        }

        $this->dbTurnos->desconectar();

        return $especialidades;
    }

}