<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'usuario.class.php';
include_once negocio.'cama.class.php';
include_once 'utils.php';

class CamaDatabaseLinker
{
    var $dbturnos;
    
    function CamaDatabaseLinker()
    {
        $this->dbturnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getCamas()
    {
        $query="SELECT
                    c.id,
                    c.nro_cama,
                    s.detalle as sector
                FROM
                    cama c LEFT JOIN
                    sector s ON(c.idsector = s.id)
                WHERE
                    c.habilitado=true;";

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

        $Camas = array();

        for ($i = 0; $i < $this->dbturnos->querySize; $i++)
        {
            $result = $this->dbturnos->fetchRow($query);

            $Cama = new Cama();
            $Cama->setId($result['id']);
            $Cama->setNro($result['nro_cama']);
            $Cama->setSector($result['sector']);
            $Camas[] = $Cama;
        }

        $this->dbturnos->desconectar();

        return $Camas;
    }

    function getCamasEnSector($idsector)
    {
        $query="SELECT
                    c.id,
                    c.nro_cama,
                    s.detalle as sector,
                    ci.idinternacion
                FROM
                    cama_internacion ci LEFT JOIN
                    cama c ON(ci.idcama=c.id) LEFT JOIN
                    sector s ON(c.idsector = s.id)
                WHERE
                    c.habilitado=true AND
                    s.id=$idsector;";
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

        $Camas = array();

        for ($i = 0; $i < $this->dbturnos->querySize; $i++)
        {
            $result = $this->dbturnos->fetchRow($query);

            $Cama = new Cama();
            $Cama->setId($result['id']);
            $Cama->setNro($result['nro_cama']);
            $Cama->setSector($result['sector']);
            $Cama->setIdInternacion($result['idinternacion']);
            $Camas[] = $Cama;
        }

        $this->dbturnos->desconectar();

        return $Camas;
    }

    function getCama($id)
    {
        $query="SELECT
                    c.id,
                    c.nro_cama,
                    s.detalle as sector
                FROM
                    cama c LEFT JOIN
                    sector s ON(c.idsector = s.id)
                WHERE
                    c.habilitado=true AND
                    c.id=$id;";

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

        $Cama = new Cama();
        $Cama->setId($result['id']);
        $Cama->setNro($result['nro_cama']);
        $Cama->setSector($result['sector']);

        $this->dbturnos->desconectar();

        return $Cama;
    }

    private function getCamas2($page, $rows, $filters)
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
                    c.id,
                    c.nro_cama,
                    s.detalle as sector
                FROM
                    cama c LEFT JOIN
                    sector s ON(c.idsector = s.id)
                WHERE
                    c.habilitado=true ".$where."
                LIMIT $rows OFFSET $offset;";
        
        try
        {
            $this->dbturnos->ejecutarQuery($query);
        } 
        catch (Exception $e)
        {
            throw new Exception("Error Processing Request", 1);
        }

        $Camas = array();

        for ($i = 0; $i < $this->dbturnos->querySize; $i++)
        {
            $result = $this->dbturnos->fetchRow($query);

            $Cama = new Cama();
            $Cama->setId($result['id']);
            $Cama->setNro($result['nro_cama']);
            $Cama->setSector($result['sector']);
            $Camas[] = $Cama;
        }

        $this->dbturnos->desconectar();

        return $Camas;
    }

    private function getCantidadCamas($filters = null)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM
                    cama
                WHERE
                    habilitado=true;";
        
        $this->dbturnos->ejecutarQuery($query);

        $result = $this->dbturnos->fetchRow($query);

        $ret = $result['cantidad'];

        return $ret;
    }

    function getCamasJson($page, $rows, $filters)
    {
        $response = new stdClass();

        $this->dbturnos->conectar();

        $response->page = $page;
        $response->total = ceil($this->getCantidadCamas($filters) / $rows);
        $response->records = $this->getCantidadCamas($filters);

        $subarray = $this->getCamas2($page, $rows, $filters);

        $this->dbturnos->desconectar();

        for ($i=0; $i < count($subarray) ; $i++) 
        {
            $cama = $subarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $cama->getId(); 
            $row = array();
            $row['nro_cama'] = $cama->getNro();
            $row['idsector'] = $cama->getSector();
            $row['myac'] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Nro']= 'nro';
        $response->userdata['sector']= 'sector';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    public function crearCama($arraySector, $usuario)
    {
        $response = new stdClass();

        if(!$this->existeNroCama($arraySector['nro_cama']))
        {
            $query="INSERT INTO
                        cama (
                            `nro_cama`,
                            `idsector`,
                            `fecha_creacion`,
                            `idusuario`,
                            `habilitado`)
                    VALUES (
                            '".$arraySector['nro_cama']."',
                            ".$arraySector['idsector'].",
                            now(),
                            '".$usuario."',
                            true);";
        
            try
            {
                $this->dbturnos->conectar();
                $this->dbturnos->ejecutarAccion($query);
                $response->message = "Cama Agregada";
                $response->ret = true;
            }
            catch (Exception $e)
            {
                $this->dbturnos->desconectar();
                $response->message = "Ocurrio un error al crear la cama!";
                $response->ret = false;
            }

            $idCama = $this->dbturnos->ultimoIdInsertado();

            $camaParaInternar = $this->crearCamaInternacion($idCama);
            
            $this->dbturnos->desconectar();
        }
        else
        {
            $response->message = "El nro de cama ya existe!";
            $response->ret = false;
        }

        return $response;
    }

    public function eliminarCama($id)
    {
        $query="UPDATE
                    cama
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

    public function eliminarCama2($data)
    {
        $response = new stdClass();
        
        $query="UPDATE
                    cama
                SET
                    habilitado = false
                WHERE
                    id = ".$data['id'].";";

         try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarAccion($query);
            $response->message = "Cama eliminada";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            $response->message = "Ocurrio un error eliminando la cama.";
            $response->ret = false;
        }

        $this->dbturnos->desconectar();

        return $response;
    }

    public function modificarCama($data)
    {
        $response = new stdClass();

        if(!$this->existeNroCama($data['nro_cama']))
        {
            $query="UPDATE
                        cama
                    SET
                        nro_cama='".$data['nro_cama']."',
                        idsector = ".$data['idsector']."
                    WHERE
                        id=".Utils::phpIntToSQL($data['id']).";";
            try
            {
                $this->dbturnos->conectar();
                $this->dbturnos->ejecutarAccion($query);
                $response->message = "Cama modificada";
                $response->ret = true;

            }
            catch (Exception $e)
            {
                $this->dbturnos->desconectar();
                $response->message = "Ocurrio un error al editar la cama!";
                $response->ret = false;
            }
            $this->dbturnos->desconectar();

        }
        else
        {
            $response->message = "El nro de cama ya existe";
            $response->ret = false;
        }
        
        return $response;
    }

    function existeNroCama($nroCama)
    {
        $query="SELECT
                    count(*) AS cantidad
                FROM
                    cama
                WHERE
                    nro_cama=$nroCama AND
                    habilitado=true;";
        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error consultando el nrod de cama!", 1);
        }

        $result = $this->dbturnos->fetchRow($query);

        $this->dbturnos->desconectar();

        return $result['cantidad']!="0";
    }

    function crearCamaInternacion($idCama)
    {
        $query="INSERT INTO
                    cama_internacion(
                        `idcama`,
                        `idinternacion`,
                        `fecha_creacion` )
                VALUES (
                        $idCama,
                        0,
                        now()
                        );";
        
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

    function internarnarEnCama($idCama, $idinternacion, $idusuario)
    {
        $query="UPDATE
                    cama_internacion
                SET
                    `idinternacion` = $idinternacion,
                    `fecha_creacion` = now(),
                    `idusuario`= $idusuario
                WHERE
                    `idcama`=$idCama and`idinternacion`='0';";
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

    function salidaInternacionCama($idCama, $idusuario)
    {
        $query="UPDATE
                    cama_internacion
                SET
                    `idinternacion` = '0',
                    `fecha_creacion` = now(),
                    `idusuario`= $idusuario
                WHERE
                    `idcama`=$idCama;";
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

    function existeInternadoEnCama($idCama)
    {
        $query="SELECT
                    `idinternacion`
                FROM
                    cama_internacion
                WHERE
                    idCama=$idCama;";
        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error consultando si existe un internado en la cama!", 1);
        }

        $result = $this->dbturnos->fetchRow($query);

        $this->dbturnos->desconectar();

        return $result['idinternacion']!="0";
    }

    function getCamasLibresEnSector($idsector)
    {
        $std = new stdClass();

        $std->ret=false;

        $std->datos= "";

        $camas = $this->getCamasEnSector($idsector);

        $camasLibres = array();

        for ($i=0; $i < count($camas) ; $i++)
        {
            if($camas[$i]->getIdInternacion()==0)
            {
                $camasLibres[] = $camas[$i];
            }
        }
        
        if($camas!=false)
        {
            $std->ret = true;

            $std->datos = $camasLibres;
        }

        return $std;
    }

    function getIdCamaEnInternacion($idInternacion)
    {
        $query="SELECT
                    `idCama`
                FROM
                    cama_internacion
                WHERE
                    idinternacion=$idInternacion;";
        try
        {
            $this->dbturnos->conectar();
            $this->dbturnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbturnos->desconectar();
            throw new Exception("Error consultando si existe un internado en la cama!", 1);
        }

        $result = $this->dbturnos->fetchRow($query);

        $this->dbturnos->desconectar();

        return $result['idCama'];
    }

}