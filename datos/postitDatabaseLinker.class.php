<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'usuarioDatabaseLinker.class.php';
include_once negocio.'postit.class.php';

class PostitDatabaseLinker
{
    var $dbTurnos;
    var $dbUsuario;

    function PostitDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        $this->dbUsuario = new UsuarioDatabaseLinker();
    }

    function nuevoPostit($idusuario, $detalle)
    {
        $query="INSERT INTO
                    postit(
                        `idusuario`,
                        `descripcion`,
                        `fecha_creacion`,
                        `habilitado`)
                VALUES (
                        $idusuario,
                        ".Utils::phpStringToSQL($detalle).",
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
            return false;
        }

        $this->dbTurnos->desconectar();

        return true;   
    }

    function getPostitDeUsuario($idusuario)
    {
        $query="SELECT
                    id,
                    idusuario,
                    descripcion,
                    fecha_creacion
                FROM
                    postit
                WHERE
                    habilitado=true AND 
                    idusuario=$idusuario;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $postit = new Postit();
            $result = $this->dbTurnos->fetchRow($query);
            $usuario = $this->dbUsuario->getUsuarioPorId($result['idusuario']);
            $postit->setId($result['id']);
            $postit->setUsuario($usuario);
            $postit->setDescripcion(Utils::phpStringToHTML($result['descripcion']));

            $ret[] = $postit;
        }

        return $ret;
    }

}