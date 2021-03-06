<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';

class GeneralesDatabaseLinker
{
    var $dbTurnos;

    function GeneralesDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getDescripcionTipoDocumento($id)
    {
        $query="SELECT 
                    id,
                    detalle_corto,
                    detalle
                FROM    
                    tipo_documento
                WHERE
                    id = $id;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos o hacer la consulta", 17052013);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getTiposDocumentos()
    {
        $query="SELECT 
                    id,
                    detalle_corto
                FROM    
                    tipo_documento;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos o hacer la consulta", 17052013);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function mesesConTurnosAtendidos($anio)
    {
        $query="SELECT
                    MONTH(t.fecha_creacion) as id,
                    m.detalle
                FROM
                    turno t LEFT JOIN
                    mes m ON(MONTH(t.fecha_creacion) = m.id)
                WHERE
                    YEAR(t.fecha_creacion) = $anio
                    group by MONTH(t.fecha_creacion);";

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
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function aniosConTurnosAtendidos()
    {
        $query="SELECT
                    YEAR(t.fecha_creacion) as ano
                FROM
                    turno t
                group by YEAR(t.fecha_creacion);";

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
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getPaises()
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    pais
                ORDER BY descripcion ASC;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar los paises", 17052013);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getProvincias($idpais)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    provincia
                WHERE
                    idresapro_pais=$idpais;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar las provincias", 17052013);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getPartidos($idpais, $idprovincia)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    partido
                WHERE
                    idresapro_pais = $idpais AND
                    idresapro_provincia = $idprovincia;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar los partidos", 17052013);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getLocalidades($idpais, $idprovincia, $idpartido)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    localidad
                WHERE
                    idresapro_pais = $idpais AND
                    idresapro_provincia = $idprovincia AND
                    idresapro_partido=$idpartido;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar las localidades", 17052013);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function getPais($id)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    pais
                WHERE
                    idresapro=$id;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar los paises", 17052013);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function getProvincia($idpais, $idprovincia)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    provincia
                WHERE
                    idresapro_pais=$idpais AND
                    idresapro=$idprovincia;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar las provincias", 17052013);
        }
        $ret =  $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function getPartido($idpais, $idprovincia, $idpartido)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    partido
                WHERE
                    idresapro_pais = $idpais AND
                    idresapro_provincia = $idprovincia AND
                    idresapro = $idpartido;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar los partidos", 17052013);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function getLocalidad($idpais, $idprovincia, $idpartido, $idlocalidad)
    {
        $query="SELECT
                    idresapro as id,
                    descripcion
                FROM
                    localidad
                WHERE
                    idresapro_pais = $idpais AND
                    idresapro_provincia = $idprovincia AND
                    idresapro_partido=$idpartido AND
                    idresapro=$idlocalidad;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error al conectar con la base de datos para consultar las localidades", 17052013);
        }
        
        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    public function mesesConPacientesInternados($anio)
    {
        $query="SELECT
                    MONTH(i.fecha_creacion) as id,
                    m.detalle
                FROM
                    internacion i LEFT JOIN
                    mes m ON(MONTH(i.fecha_creacion) = m.id)
                WHERE
                    YEAR(i.fecha_creacion) = 2016
                    group by MONTH(i.fecha_creacion);";

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
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function aniosConPacientesInternados()
    {
        $query="SELECT
                    YEAR(i.fecha_creacion) as ano
                FROM
                    internacion i
                group by YEAR(i.fecha_creacion);";

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
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

}