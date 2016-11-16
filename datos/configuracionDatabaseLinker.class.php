<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once negocio.'configuracion.class.php';

class ConfiguracionDatabaseLinker
{
    var $dbTurnos;

    function ConfiguracionDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getConfiguracion($idCentro)
    {
        $query="SELECT
                    id,
                    idcentro,
                    color,
                    nombre_logo,
                    fecha_creacion
                FROM
                    configuracion
                WHERE
                    idcentro=$idCentro;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar los centros de la empresa Nro $idCentro", 201230);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $configuracion = new Configuracion();
        $configuracion->setId($ret['id']);
        $configuracion->setCentro($ret['idcentro']);
        $configuracion->setColor($ret['color']);
        $configuracion->setNombreLogo($ret['nombre_logo']);
        $configuracion->getFechaCreacion($ret['fecha_creacion']);

        $this->dbTurnos->desconectar();

        return $configuracion;
    }

}

?>