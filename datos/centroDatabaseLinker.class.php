<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once negocio.'centro.class.php';

class CentroDatabaseLinker
{
    var $dbTurnos;

    function CentroDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getCentros($idEmpresa)
    {
        $query="SELECT
                    id,
                    detalle,
                    direccion,
                    telefono
                FROM
                    centro
                WHERE
                    idempresa=$idEmpresa AND
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
            throw new Exception("No se pudo consultar los centros de la empresa Nro $idEmpresa", 201230);
        }

        $centros = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret = $this->dbTurnos->fetchRow($query);

            $centro = new Centro();
            $centro->setId($ret['id']);
            $centro->setDetalle($ret['detalle']);
            $centro->setDireccion($ret['direccion']);
            $centro->setTelefono($ret['telefono']);

            $centros[] = $centro;
        }

        $this->dbTurnos->desconectar();

        return $centros;
    }

    

}