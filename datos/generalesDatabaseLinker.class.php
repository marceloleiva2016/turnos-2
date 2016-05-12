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

    function getProvincias()
    {
        $query="SELECT id, provincia_nombre FROM provincia;";

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

    function getCiudades($cp)
    {
        $query="SELECT id as idc, ciudad_nombre FROM ciudad WHERE cp=$cp;";

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

    function getCiudades_json($cp)
    {
        $std = new stdClass();

        $cpds = $this->getCiudades($cp);

        $std->ret=false;

        $std->datos= "";

        if($cpds!=false)
        {
            $std->ret = true;

            $std->datos = $cpds;
        }

        return $std;
    }

    function calcularEdad($fecha_nac)
    {
        //fecha actual

        $dia=date("j");
        $mes=date("n");
        $ano=date("Y");

        //fecha de nacimiento
        $dianaz=(int) substr($fecha_nac, 8, 2);
        $mesnaz=(int) substr($fecha_nac, 5, 2);
        $anonaz=(int) substr($fecha_nac, 0, 4);

        //si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual

        if(($mesnaz == $mes) && ($dianaz > $dia))
        {
            $ano=($ano-1);
        }

        //si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual

        if($mesnaz > $mes)
        {
            $ano=($ano-1);
        }

        //ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad

        $edad=($ano-$anonaz);

        return $edad;
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

}