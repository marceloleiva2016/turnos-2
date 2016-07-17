<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'subespecialidad.class.php';
include_once negocio.'especialidad.class.php';

class SubespecialidadDatabaseLinker
{
    var $dbTurnos;

    function SubespecialidadDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }
    
    function getSubespecialidad($idsubespecialidad)
    {
    	$query="SELECT
                    id,
                    detalle,
                    idespecialidad
                FROM
                    subespecialidad
                WHERE
    				id = $idsubespecialidad AND
                    habilitado=1";
    	try
    	{
    		$this->dbTurnos->conectar();
    		$this->dbTurnos->ejecutarAccion($query);
    	}
    	catch (Exception $e)
    	{
    		$this->dbTurnos->desconectar();
    		throw new Exception("Error Processing Request", 1);
    	}
    	
    		$result = $this->dbTurnos->fetchRow($query);
    		$subespecialidad = new Subespecialidad();
    		$subespecialidad->setId($result['id']);
    		$subespecialidad->setDetalle($result['detalle']);
    		$subespecialidad->setEspecialidad($result['idespecialidad']);
    		$subespecialidades[] = $subespecialidad;
    	
    	$this->dbTurnos->desconectar();
    	
    	return $subespecialidad;
    }

    function getSubespecialidades()
    {
        $query="SELECT
                    id,
                    detalle,
                    idespecialidad
                FROM 
                    subespecialidad
                WHERE
                    habilitado=1";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $subespecialidades = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $subespecialidad = new Subespecialidad();
            $subespecialidad->setId($result['id']);
            $subespecialidad->setDetalle($result['detalle']);
            $subespecialidad->setEspecialidad($result['idespecialidad']);
            $subespecialidades[] = $subespecialidad;
        }

        $this->dbTurnos->desconectar();

        return $subespecialidades;
    }

    function getSubespecialidadesEnEspecialidad($idEspecialidad)
    {
        $query="SELECT
                    id,
                    detalle
                FROM 
                    subespecialidad
                WHERE 
                    habilitado=1 AND 
                    idespecialidad=$idEspecialidad;";

        $response = new stdClass();

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);    
            $response->message = "Subspecialidades consultadas";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $response->message = "Error Consultando las subespecialidades";
            $response->ret = false;
        }

        $subespecialidades = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $subespecialidad = new Subespecialidad();
            $subespecialidad->setId($result['id']);
            $subespecialidad->setDetalle($result['detalle']);
            $subespecialidad->setEspecialidad($idEspecialidad);
            $subespecialidades[] = $subespecialidad;
        }

        $response->data = $subespecialidades;

        $this->dbTurnos->desconectar();

        return $response;
    }

    function getSubspecialidadesConConsultoriosDeDemandaActivos($idEspecialidad)
    {
        $query="SELECT
                    s.id,
                    s.detalle
                FROM
                    subespecialidad s LEFT JOIN
                    consultorio c ON (c.idsubespecialidad = s.id)
                WHERE
                    c.habilitado=1 AND
                    c.idtipo_consultorio=1 AND
                    s.habilitado=1 AND
                    s.idespecialidad=$idEspecialidad
                GROUP BY 
                    s.id;";

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

        $subespecialidades = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $Subespecialidad = new Subespecialidad();
            $Subespecialidad->setId($result['id']);
            $Subespecialidad->setDetalle($result['detalle']);
            $subespecialidades[] = $Subespecialidad;
        }

        $this->dbTurnos->desconectar();

        return $subespecialidades;
    }

    function getSubspecialidadesConConsultoriosDeDemandaActivos_json($cp)
    {
        $std = new stdClass();

        $subesp = $this->getSubspecialidadesConConsultoriosDeDemandaActivos($cp);

        $std->ret=false;

        $std->datos= "";

        if($subesp!=false)
        {
            $std->ret = true;

            $std->datos = $subesp;
        }

        return $std;
    }

    function obtenerEspecialidadPadre($idsubespecialidad)
    {
        $query="SELECT 
                    idespecialidad
                FROM 
                    subespecialidad
                WHERE
                    id=$idsubespecialidad;";
                    
         try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando la especialidad padre de la subespecialidad con id=".$idsubespecialidad, 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idespecialidad'];
    }

    function crearSubespecialidad($arraySubespecialidad, $usuario)
    {
        $response = new stdClass();
        $query="INSERT INTO 
                    subespecialidad
                        (`detalle`,
                        `idespecialidad`,
                        `habilitado`,
                        `idusuario`
                        )
                VALUES
                    (
                        '".$arraySubespecialidad['detalle']."',
                        '".$arraySubespecialidad['idespecialidad']."',
                        1,
                        '".$usuario."'
                    );";



        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "subespecialidad Agregada";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $response->message = "Ocurrio un error al crear la subespecialidad";
            $response->ret = false;
        }

        return $response;
    }

    function borrarSubespecialidad($id)
    {
        $query="UPDATE
                    subespecialidad
                SET
                    `habilitado`='0'
                WHERE
                    `id`=$id;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
        }
        catch (Exception $e)
        {
            return false;
        }

        return true;
    }

    private function getSubespecialidades2($page, $rows, $filters)
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
                    sub.id,
                    sub.detalle,
                    esp.detalle as especialidad
                FROM
                    subespecialidad sub LEFT JOIN  
                    especialidad esp ON(sub.idespecialidad = esp.id)
                WHERE
                    sub.habilitado = true ".$where."
                LIMIT $rows OFFSET $offset;";


        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $subespecialidad = new Subespecialidad();
            $subespecialidad->setId($result['id']);
            $subespecialidad->setDetalle($result['detalle']);
            $subespecialidad->setEspecialidad($result['especialidad']);
            $ret[] = $subespecialidad;
        }

        return $ret;
    }

    private function getCantidadSubespecialidades($filters = null)
    {

        $where = "WHERE sub.habilitado = true ";

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    subespecialidad sub";
        $query .= " " . $where;
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }


    function getSubespecialidadesJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $subarray = $this->getSubespecialidades2($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadSubespecialidades($filters) / $rows);
        $response->records = $this->getCantidadSubespecialidades($filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($subarray) ; $i++) 
        {
            $subespecialidad = $subarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $subespecialidad->id; 
            $row = array();
            $row[] = $subespecialidad->id;
            $row[] = $subespecialidad->detalle;
            $row[] = $subespecialidad->especialidad;
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Id']= 'Id';
        $response->userdata['detalle']= 'detalle';
        $response->userdata['idespecialidad']= 'idespecialidad';
        $response->userdata['habilitado']= 'habilitado';
        $response->userdata['idusuario']= 'idusuario';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function modificarSubespecialidad($data)
    {

        $response = new stdClass();

        $query= "UPDATE subespecialidad SET detalle = '".$data['detalle']."' WHERE id = ".$data['id'].";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
            $this->dbTurnos->desconectar();
            $response->message = "Subespecialidad modificada";
            $response->ret = true;

        }
        catch (Exception $e)
        {
            $response->message = "Hubo un error modificando la subespecialidad.";
            $response->ret = false;
        }

        return $response;
    }

    function eliminarSubespecialidad($data)
    {
        $query = "UPDATE subespecialidad SET habilitado = 0 WHERE id = ".$data['id'].";";

         try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
            $this->dbTurnos->desconectar();
            $response->message = "Subespecialidad eliminada";
            $response->ret = true;

        }
        catch (Exception $e)
        {
            $response->message = "Hubo un error eliminando la subespecialidad.";
            $response->ret = false;
        }

        return $response;
    }

    function getSubspecialidadesConConsultoriosProgramadosActivos_json($cp)
    {
        $std = new stdClass();

        $subesp = $this->getSubspecialidadesConConsultoriosProgramadosActivos($cp);

        $std->ret=false;

        $std->datos= "";

        if($subesp!=false)
        {
            $std->ret = true;

            $std->datos = $subesp;
        }

        return $std;
    }
    
    function getSubspecialidadesConConsultoriosProgramadosActivos($idEspecialidad)
    {
    	$query="SELECT
    	s.id,
    	s.detalle
    	FROM
    	subespecialidad s LEFT JOIN
    	consultorio c ON (c.idsubespecialidad = s.id)
    	WHERE
    	c.habilitado=1 AND
    	c.idtipo_consultorio=2 AND
    	s.habilitado=1 AND
    	s.idespecialidad=$idEspecialidad
    	GROUP BY
    	s.id;";
    
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
    
    	$subespecialidades = array();
    
    	for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
    	{
    		$result = $this->dbTurnos->fetchRow($query);
    		$Subespecialidad = new Subespecialidad();
    		$Subespecialidad->setId($result['id']);
    		$Subespecialidad->setDetalle($result['detalle']);
    		$subespecialidades[] = $Subespecialidad;
    	}
    
    	$this->dbTurnos->desconectar();
    
    	return $subespecialidades;
    }
}
