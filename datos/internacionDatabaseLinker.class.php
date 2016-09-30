<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';

class InternacionDatabaseLinker
{
    var $dbTurnos;

    function InternacionDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function obtenerVariablesInternacion($idInternacion)
    {
        /*van a ser variables basicas para la lectura del formulario principal*/

        $query="SELECT
                    t.tipodoc as tipodoc,
                    t.nrodoc as nrodoc,
                    c.idprofesional as idprofesional,
                    c.idsubespecialidad as idsubespecialidad,
                    t.idtipo_atencion as idtipo_atencion
                FROM
                    internacion t join 
                    consultorio c on (t.idconsultorio = c.id)
                WHERE
                    t.id=$idInternacion;";
                    
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e) 
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando las variables del turno", 1);              
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;
    }

    function crearInternacion($idatencionPredecesora, $motivoIngreso, $idDiagnosticoIngreso, $idCama, $idusuario)
    {
        $response = new stdClass();

        $query="INSERT INTO
                    internacion(
                        `idatencion_predecesora`,
                        `motivo_ingreso`,
                        `ididiagnostico_ingreso`,
                        `fecha_creacion`,
                        `idusuario`,
                        `habilitado`)
                VALUES (
                        $idatencionPredecesora ,
                        ".Utils::phpStringToSQL($motivoIngreso)." ,
                        $idDiagnosticoIngreso ,
                        now() ,
                        $idusuario ,
                        true
                        );";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "Cama Agregada";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $response->message = "Ocurrio un error al crear la cama!";
            $response->ret = false;
        }

        $idinternacion = $this->dbTurnos->ultimoIdInsertado();

        $this->insertarEnLog($idinternacion, $idCama, $idusuario);
        
        $this->dbTurnos->desconectar();

        return $response;
    }

    function insertarEnLog($idinternacion, $idcama, $idusuario)
    {
        $query="INSERT INTO
                    internacion_log_cama (
                        `idinternacion`,
                        `idcama`,
                        `idusuario`,
                        `fecha_creacion`,
                        `habilitado`)
                VALUES (
                        $idinternacion,
                        $idcama,
                        $idusuario,
                        now(),
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
}