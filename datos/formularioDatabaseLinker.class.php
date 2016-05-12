<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';

class FormularioDatabaseLinker
{
    var $dbTurnos;

    function FormularioDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getFormulario($idformulario)
    {
        $query="SELECT
                    id,
                    nombre,
                    descripcion,
                    ubicacion
                FROM
                    formulario
                WHERE
                    id=".$idformulario." AND
                    habilitado=1;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        if(count($result)==0)
        {
            return false;
        }

        return $result;
    }

    function especialidadTieneFormularioDefinido($idEspecialidad, $idTipoAtencion)
    {
        $query="SELECT
                    f.id as id
                FROM
                    formulario f LEFT JOIN
                    formulario_especialidad fe ON(f.id=fe.idformulario) LEFT JOIN 
                    tipo_atencion_formulario tf ON(tf.idformulario=f.id)
                WHERE
                    fe.idespecialidad=$idEspecialidad AND
                    tf.idtipo_atencion=$idTipoAtencion AND
                    f.esDefault = false;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            throw new Exception("Error al conectar con la base de datos", 17052013);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();
        
        return $result['id'];
    }

    function formularioDefautlEnTipoAtencion($idTipoAtencion)
    {
        $query="SELECT
                    f.id as id
                FROM
                    formulario f LEFT JOIN
                    tipo_atencion_formulario tf ON(tf.idformulario=f.id)
                WHERE
                    tf.idtipo_atencion = $idTipoAtencion AND
                    f.esDefault = true;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error ", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function obtenerIdFormulario($idSubespecialidad, $idTipoAtencion)
    {
        $dbSub = new SubespecialidadDatabaseLinker();

        $idEspecialidad = $dbSub->obtenerEspecialidadPadre($idSubespecialidad);

        $idFormulario = $this->especialidadTieneFormularioDefinido($idEspecialidad, $idTipoAtencion);
        
        if($idFormulario==null)
        {
            $idFormulario = $this->formularioDefautlEnTipoAtencion($idTipoAtencion);
        }
        if($idFormulario==null)
        {
            echo "No se pudo obtener el formulario";
        }
        
        return $idFormulario;
    }
}