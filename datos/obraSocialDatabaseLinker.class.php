<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';

class ObraSocialDatabaseLinker
{
    var $dbTurnos;

    function ObraSocialDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getObrasSocialesActivas()
    {
        $query="SELECT 
                    id,
                    nro_rnos,
                    detalle_corto,
                    detalle
                FROM
                    obra_social
                WHERE
                    habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo consultar las atenciones del paciente", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function eliminarFichaoOsoc($id)
    {
        $query="UPDATE
                    paciente_obra_social
                SET
                    habilitado=false
                WHERE
                    id=$id;";
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

    function crearFichaOsoc($data)
    {
        $query="INSERT INTO
                    `paciente_obra_social` (
                        `tipodoc`,
                        `nrodoc`, 
                        `idobra_social`, 
                        `nro_afiliado`, 
                        `empresa_nombre`, 
                        `empresa_direccion`, 
                        `fecha_emision`, 
                        `fecha_vencimiento`,
                        `fecha_creacion`,
                        `idusuario`, 
                        `habilitado`) 
                VALUES (
                        ".Utils::phpIntToSQL($data['tipodoc']).",
                        ".Utils::phpIntToSQL($data['nrodoc']).",
                        ".Utils::phpIntToSQL($data['osoc']).",
                        ".Utils::phpIntToSQL($data['osoc_nro_afiliado']).",
                        ".Utils::phpStringToSQL($data['osoc_empresa']).",
                        ".Utils::phpStringToSQL($data['osoc_direccion']).",
                        ".$data['osoc_fecha_emision'].",
                        ".$data['osoc_fecha_vencimiento'].",
                        now(),
                        ".Utils::phpIntToSQL($data['idusuario']).",
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

    function getObraSocialPaciente($tipodoc, $nrodoc)
    {
        $query="SELECT
                    pos.id as id,
                    os.detalle as obra_social,
                    pos.nro_afiliado,
                    pos.empresa_nombre,
                    pos.empresa_direccion,
                    pos.fecha_emision,
                    pos.fecha_vencimiento,
                    pos.fecha_creacion
                FROM
                    paciente_obra_social pos LEFT JOIN
                    obra_social os ON(pos.idobra_social=os.id)
                WHERE
                    pos.tipodoc=$tipodoc AND
                    pos.nrodoc=$nrodoc AND
                    pos.habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo consultar la obra social del paciente", 201230);
        }

        $ret= $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        if($ret==false){
            $obsoc= array();
            $obsoc['id'] = 0;
            $obsoc['obra_social'] = 'SIN OBRA SOCIAL';
            $obsoc['nro_afiliado'] = '';
            $obsoc['empresa_nombre'] = '';
            $obsoc['empresa_direccion'] = '';
            $obsoc['fecha_emision'] = '';
            $obsoc['fecha_vencimiento'] = '';
            $obsoc['fecha_creacion'] = '';

            return $obsoc;
        }

        return $ret;
    }

    function getOsocFiltrada($nombre)
    {
        $query="SELECT
                    id,
                    nro_rnos,
                    detalle_corto,
                    detalle as descripcion
                FROM 
                    obra_social
                WHERE
                    (detalle like '%".$nombre."%' OR
                    detalle_corto like '%".$nombre."%') AND
                    habilitado = true;";

        try {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        } catch (Exception $e) {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo consultar las obras sociales", 201230);            
        }

        $obrasSociales = array();

        for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $obrasSociales[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $obrasSociales;
    }

    function getObraSocial($id)
    {
        $query="SELECT
                    id,
                    detalle_corto,
                    detalle
                FROM
                    obra_social
                WHERE
                    id=$id;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo consultar la obra social del paciente", 201230);
        }

        $ret= $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

}