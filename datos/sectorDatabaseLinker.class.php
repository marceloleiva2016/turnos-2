<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'usuario.class.php';
include_once negocio.'sector.class.php';
include_once 'utils.php';

class sectorDatabaseLinker
{
    var $dbturnos;
    
    function sectorDatabaseLinker()
    {
        $this->dbturnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getSectores()
    {
        $query="SELECT
                    s.id,
                    s.detalle,
                    e.detalle as especialidad,
                    count(c.id) as cant_camas
                FROM
                    sector s LEFT JOIN
                    especialidad e ON(s.idespecialidad = e.id) LEFT JOIN
                    cama c ON(c.idsector=s.id)
                WHERE
                    s.habilitado=TRUE
                GROUP BY
                    s.id;";

        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $Sectors = array();

        for ($i = 0; $i < $this->dbturnos->querySize; $i++)
        {
            $result = $this->dbturnos->fetchRow($query);
            if($result['cant_camas']!=0){
                $Sector = new Sector();
                $Sector->setId($result['id']);
                $Sector->setDetalle($result['detalle']);
                $Sector->setEspecialidad($result['especialidad']);
                $Sectors[] = $Sector;    
            }
        }

        $this->dbturnos->desconectar();

        return $Sectors;
    }

    function getSector($id)
    {
        $query="SELECT
                    s.id,
                    s.detalle,
                    e.detalle as especialidad
                FROM
                    sector s LEFT JOIN
                    especialidad e ON(s.idespecialidad = e.id)
                WHERE
                    s.habilitado=true AND
                    s.id=$id;";

        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $result = $this->dbturnos->fetchRow($query);

        $Sector = new Sector();
        $Sector->setId($result['id']);
        $Sector->setDetalle($result['detalle']);
        $Sector->setEspecialidad($result['especialidad']);

        $this->dbturnos->desconectar();

        return $Sector;
    }


    private function getSectores2($page, $rows, $filters)
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
                    s.id,
                    s.detalle,
                    e.detalle as especialidad
                FROM
                    sector s LEFT JOIN
                    especialidad e ON(s.idespecialidad = e.id)
                WHERE
                    s.habilitado=true ".$where."
                LIMIT $rows OFFSET $offset;";
        
        try {
            $this->dbturnos->ejecutarQuery($query);    
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }

        $Sectores = array();

        for ($i = 0; $i < $this->dbturnos->querySize; $i++)
        {
            $result = $this->dbturnos->fetchRow($query);

            $Sector = new Sector();
            $Sector->setId($result['id']);
            $Sector->setDetalle($result['detalle']);
            $Sector->setEspecialidad($result['especialidad']);
            $Sectores[] = $Sector;
        }

        $this->dbturnos->desconectar();

        return $Sectores;
    }

    private function getCantidadComponentes($filters = null)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM
                    sector
                WHERE
                    habilitado=true;";
        
        $this->dbturnos->ejecutarQuery($query);

        $result = $this->dbturnos->fetchRow($query);

        $ret = $result['cantidad'];

        return $ret;
    }

    function getSectoresJson($page, $rows, $filters)
    {
        $response = new stdClass();

        $this->dbturnos->conectar();

        $response->page = $page;
        $response->total = ceil($this->getCantidadComponentes($filters) / $rows);
        $response->records = $this->getCantidadComponentes($filters);

        $subarray = $this->getSectores2($page, $rows, $filters);

        $this->dbturnos->desconectar();

        for ($i=0; $i < count($subarray) ; $i++) 
        {
            $sector = $subarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $sector->id; 
            $row = array();
            $row['id'] = $sector->getId();
            $row['detalle'] = $sector->getDetalle();
            $row['idespecialidad'] = $sector->getEspecialidad();
            $row['myac'] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['id']= 'id';
        $response->userdata['detalle']= 'detalle';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    public function crearSector($arraySector, $usuario)
    {
        $response = new stdClass();

        $existe = $this->existeSectorConDetalle($arraySector['detalle']);

        if(!$existe)
        {
            $query="INSERT INTO 
                        sector (
                            `detalle`,
                            `idespecialidad`,
                            `habilitado`,
                            `idusuario`,
                            `fecha_creacion`
                    ) VALUES (
                            '".$arraySector['detalle']."',
                            ".$arraySector['idespecialidad'].",
                            1,
                            '".$usuario."',
                            now()
                        );";

            try
            {
                $this->dbturnos->conectar();
                $this->dbturnos->ejecutarAccion($query);    
                $response->message = "Sector Agregado";
                $response->ret = true;
            }
            catch (Exception $e)
            {
                $this->dbturnos->desconectar();
                
            }
            $this->dbturnos->desconectar();
        }
        else
        {
            $response->message = "El detalle del sector que intenta crear ya existe!";
            $response->ret = false;
        }

        return $response;
    }

    private function existeSectorConDetalle($detalle)
    {
        $query="SELECT
                    *
                FROM
                    sector
                WHERE
                    detalle='".$detalle."' AND
                    habilitado=TRUE";
        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error si el paciente se encuentra internado!", 1);
        }

        $result = $this->dbturnos->fetchRow($query);

        $this->dbturnos->desconectar();

        return $result!=null;
    }

    public function eliminarSector($id)
    {
        $query="UPDATE
                    sector
                SET
                    habilitado= false
                WHERE
                    `id` = $id;";
        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarAccion($query);    
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            return false;
        }
        $this->dbturnos->desconectar();

        return true;
    }

    public function eliminarSector2($data)
    {
        $response = new stdClass();
        
        $query="UPDATE
                    sector
                SET
                    habilitado = false
                WHERE
                    id = ".$data['id'].";";

         try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarAccion($query);
            $response->message = "Sector eliminado";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            $response->message = "Ocurrio un error eliminando el sector.";
            $response->ret = false;
        }

        $this->dbturnos->desconectar();

        return $response;
    }

    public function modificarSector($data)
    {
        $response = new stdClass();

        $existe = $this->existeSectorConDetalle($data['detalle']);

        if(!$existe){
            $query="UPDATE
                        sector
                    SET
                        detalle='".$data['detalle']."',
                        idespecialidad = ".$data['idespecialidad']."
                    WHERE
                        id=".Utils::phpIntToSQL($data['id']).";";
            try
            {
                $this->dbturnos->conectar();
                $this->dbturnos->ejecutarAccion($query);
                $response->message = "Sector modificado";
                $response->ret = true;

            }
            catch (Exception $e)
            {
                $this->dbturnos->desconectar();
                $response->message = "Ocurrio un error al editar el Sector!";
                $response->ret = false;
            }

            $this->dbturnos->desconectar();
        }
        else
        {
            $response->message = "Ya existe un sector con el mismo detalle!";
            $response->ret = false;
        }

        return $response;
    }

}