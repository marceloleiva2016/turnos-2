<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';

class FeriadoDatabaseLinker
{

    var $dbTurnos;

    function FeriadoDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getFeriados($page, $rows, $filters)
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
                    et.id as nro,
                    et.fecha as fecha,
                    concat(p.nombre,' ',p.apellido) as afectado
                FROM
                    excepciones_turno et LEFT JOIN
                    profesional p ON(et.idprofesional=p.id)
                WHERE
                    et.fecha>=date(now()) AND
                    et.habilitado = true ".$where." 
                ORDER BY et.fecha asc
                LIMIT $rows OFFSET $offset;";

        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $retr = array();
            $result = $this->dbTurnos->fetchRow($query);

            $retr[] = $result['nro'];
            $retr[] = $result['fecha'];
            $retr[] = $result['afectado'];
            $ret[] = $retr;
        }

        return $ret;
    }

    private function getCantidadFeriados($filters = null)
    {

        $query="SELECT
                    COUNT(*) as cantidad
                FROM
                    excepciones_turno et
                WHERE
                    et.fecha>=date(now()) AND
                    et.habilitado = true";
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }

    function getFeriadosJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $ferArray = $this->getFeriados($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadFeriados($filters) / $rows);
        $response->records = $this->getCantidadFeriados($filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($ferArray) ; $i++) 
        {
            $feriados = $ferArray[$i];
            //id de fila
            $response->rows[$i]['id'] = $feriados[0];
            $row = array();
            $row[] = $feriados[0];
            $row[] = $feriados[1];
            $row[] = $feriados[2];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['nro']= 'nro';
        $response->userdata['fecha']= 'fecha';
        $response->userdata['afectado']= 'afectado';
        return json_encode($response);
    }

    function crearFeriado($fecha, $profesional, $usuario)
    {
        $query="INSERT INTO 
                    excepciones_turno (
                        `fecha`, 
                        `idprofesional`, 
                        `idusuario`, 
                        `fecha_creacion`, 
                        `habilitado`) 
                VALUES (
                        '$fecha', 
                        $profesional, 
                        $usuario, 
                        now(), 
                        true
                        );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return true;
        }

        $this->dbTurnos->desconectar();

        return false;

    }

    function existeVacacion($fecha, $profesional)
    {
        $query="SELECT
                    * 
                FROM
                    excepciones_turno
                WHERE
                    fecha = '$fecha' AND 
                    idprofesional = $profesional AND 
                    habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        if($result!=false) {
            return true;
        } else {
            return false;
        }

    }

    function existeFeriado($fecha)
    {
        $query="SELECT
                    *
                FROM
                    excepciones_turno
                WHERE
                    fecha = '$fecha' AND 
                    idprofesional = 0 AND 
                    habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        if($result!=false) {
            return true;
        } else {
            return false;
        }

    }

    function eliminarFeriado($id)
    {
        $query="UPDATE
                    excepciones_turno
                SET
                    habilitado=false
                WHERE
                    id= $id;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return true;
        }

        $this->dbTurnos->desconectar();

        return false;
    }

}
?>