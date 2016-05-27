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
                    detalle
                FROM
                    especialidad
                WHERE
                    habilitado=true;";

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
            $response->message = "Error Consultando las especialidades";
            $response->ret = false;
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

    private function getEspecialidades2($page, $rows, $filters)
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
                    id,
                    detalle
                FROM
                    especialidad
                WHERE
                    habilitado=true ".$where."
                LIMIT $rows OFFSET $offset;";

        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
             $result = $this->dbTurnos->fetchRow($query);
            $Especialidad = new Especialidad();
            $Especialidad->setId($result['id']);
            $Especialidad->setDetalle($result['detalle']);
            $ret[] = $Especialidad;
        }

        return $ret;
    }

    private function getCantidadEspecialidades($servicio, $filters = null)
    {

        $where = " ";

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM
                    especialidad
                WHERE
                    habilitado=true ";
        $query .= " " . $where;
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }


     function getEspecialidadesJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $espArray = $this->getEspecialidades2($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadEspecialidades($filters) / $rows);
        $response->records = $this->getCantidadEspecialidades($filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($espArray) ; $i++) 
        {
            $especialidad = $espArray[$i];
            //id de fila
            $response->rows[$i]['id'] = $especialidad->id; 
            $row = array();
            $row[] = $especialidad->id;
            $row[] = $especialidad->detalle;
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['id']= 'id';
        $response->userdata['especialidad']= 'especialidad';

        return json_encode($response);
    }


    function crearEspecialidad($especialidad, $idusuario)
    {
        $response = new stdClass();
        $query="INSERT INTO
                    especialidad
                        (`detalle`,
                        `habilitado`,
                        `idusuario`)
                VALUES (
                        '".$especialidad."',
                        '1',
                        '".$idusuario."');";



        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "Especialidad Agregada";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $response->message = "Ocurrio un error al crear la Especialidad";
            $response->ret = false;
        }

        return $response;
    }

}