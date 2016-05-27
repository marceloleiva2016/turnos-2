<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';

class ConsultorioDatabaseLinker
{
    var $dbTurnos;

    function ConsultorioDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getIdConsultorioDemanda($idsubespecialidad)
    {
        $query="SELECT
                    id
                FROM
                    consultorio
                WHERE
                    idsubespecialidad=$idsubespecialidad AND 
                    idtipo_consultorio=1 AND
                    habilitado=1;";
        
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el id del consultorio", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function setConsultorio($idtipo_consultorio, $subespecialidad, $profesional, $dias_anticipacion, $duracion_turno, $idhorarios, $feriados, $fecha_inicio, $fecha_fin) 
    {
        $query="INSERT INTO consultorio (
                        `idtipo_consultorio`, 
                        `idsubespecialidad`, 
                        `idprofesional`, 
                        `dias_anticipacion`, 
                        `duracion_turno`, 
                        `idhorarios`, 
                        `feriados`, 
                        `fecha_inicio`, 
                        `fecha_fin`)
                    VALUES (
                        ".Utils::phpIntToSQL($idtipo_consultorio).", 
                        ".Utils::phpIntToSQL($subespecialidad).", 
                        ".Utils::phpIntToSQL($profesional).", 
                        ".Utils::phpIntToSQL($dias_anticipacion).", 
                        ".Utils::phpIntToSQL($duracion_turno).", 
                        ".Utils::phpIntToSQL($idhorarios).", 
                        ".Utils::phpIntToSQL($feriados).", 
                        '".$fecha_inicio."', 
                        '".$fecha_fin."'
                        );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el id del consultorio", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        return $result;
    }
}