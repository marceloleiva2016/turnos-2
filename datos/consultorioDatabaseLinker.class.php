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

    function setConsultorio($usuario, $idtipo_consultorio, $subespecialidad, $profesional, $dias_anticipacion, $duracion_turno, $feriados, $fecha_inicio, $fecha_fin) 
    {

        $query="INSERT INTO consultorio (
                    `idtipo_consultorio`, 
                    `idsubespecialidad`, 
                    `idprofesional`, 
                    `dias_anticipacion`, 
                    `duracion_turno`,
                    `feriados`,
                    `fecha_inicio`,
                    `fecha_fin`,
                    `fecha_creacion`,
                    `idusuario`,
                    `habilitado`)
                VALUES (
                    ".Utils::phpIntToSQL($idtipo_consultorio).",
                    ".Utils::phpIntToSQL($subespecialidad).",
                    ".Utils::phpIntToSQL($profesional).",
                    ".Utils::phpIntToSQL($dias_anticipacion).",
                    ".Utils::phpIntToSQL($duracion_turno).",
                    $feriados, 
                    '".$fecha_inicio."',
                    '".$fecha_fin."',
                    now(),
                    '".$usuario."',
                    '1'
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
        }

        return true;
    }

    function getTiposConsultorios()
    {
        $query="SELECT
                    id,
                    detalle
                FROM
                    tipo_consultorio;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }
        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function existeConsultorio($tipoConsultorio, $subespecialidad, $profesional)
    {
        $query="SELECT
                    * 
                FROM
                    consultorio
                WHERE
                    idtipo_consultorio = ".Utils::phpIntToSQL($tipoConsultorio)." AND
                    idsubespecialidad = ".Utils::phpIntToSQL($subespecialidad)." AND
                    idprofesional = ".Utils::phpIntToSQL($profesional)." AND
                    habilitado = true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        if($result!=false) {
            return true;
        } else {
            return false;
        }
    }

    function superponeHorario($iddia, $idConsultorio, $horaDesde, $horaHasta) {
        $query="SELECT
                    *
                FROM
                    consultorio_horarios
                WHERE
                    iddia=$iddia AND
                    idconsultorio=$idConsultorio AND
                    habilitado=true AND
                    ('".$horaDesde."' between hora_desde AND hora_hasta OR
                    '".$horaHasta."' between hora_desde AND hora_hasta);";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        if($result!=false) {
            return true;
        } else {
            return false;
        }
    }

    function crearHorarioEnConsultorio($iddia, $idConsultorio, $horaDesde, $horaHasta, $idusuario) 
    {
        $query="INSERT INTO
                    consultorio_horarios
                        (`iddia`,
                        `idconsultorio`,
                        `hora_desde`,
                        `hora_hasta`,
                        `fecha_creacion`,
                        `habilitado`,
                        `idusuario`
                ) VALUES (
                        $iddia,
                        $idConsultorio,
                        '".$horaDesde."',
                        '".$horaHasta."',
                        now(),
                        true,
                        $idusuario);";

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

        return true;
    }

    function getHorarios($idConsultorio)
    {
        $query="SELECT
                    id,
                    iddia,
                    hora_desde,
                    hora_hasta
                FROM
                    consultorio_horarios
                WHERE
                    idconsultorio=$idConsultorio AND
                    habilitado=true
                ORDER BY iddia ASC, hora_desde ASC;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $ret = array();

        $ret[1] = array();//lunes
        $ret[2] = array();//martes
        $ret[3] = array();//miercoles
        $ret[4] = array();//jueves
        $ret[5] = array();//viernes
        $ret[6] = array();//sabado
        $ret[7] = array();//domingo

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $data = $this->dbTurnos->fetchRow($query);

            $ret[$data['iddia']][] = $data;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getConsultorios($idespecialidad) 
    {

        $query="SELECT 
                    c.id,
                    tc.detalle as tipo_consultorio,
                    s.detalle as subespecialidad,
                    concat(p.nombre,' ',p.apellido) as profesional
                FROM
                    consultorio c LEFT JOIN
                    tipo_consultorio tc ON(c.idtipo_consultorio=tc.id) LEFT JOIN
                    subespecialidad s ON(c.idsubespecialidad=s.id) LEFT JOIN
                    profesional p ON(c.idprofesional=p.id)
                WHERE
                    s.idespecialidad = $idespecialidad AND
                    c.habilitado = true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function borrarHorario($idHorario)
    {
        $query="UPDATE consultorio_horarios SET habilitado=false WHERE id=$idHorario;";

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

        return true;
    }

    function getConsultorio($idConsultorio)
    {
        $query="SELECT
                    c.idtipo_consultorio,
                    tp.detalle as tipo_consultorio,
                    e.id as idespecialidad,
                    e.detalle as especialidad,
                    c.idsubespecialidad,
                    s.detalle as subespecialidad,
                    c.idprofesional,
                    concat(p.nombre,' ',p.apellido) as profesional,
                    c.fecha_inicio as fecha_inicio,
                    c.fecha_fin as fecha_fin,
                    c.dias_anticipacion as dias_anticipacion, 
                    c.duracion_turno as duracion,
                    c.feriados as feriado,
                    c.habilitado as habilitado
                FROM
                    consultorio c LEFT JOIN
                    tipo_consultorio tp ON(tp.id = c.idtipo_consultorio) LEFT JOIN
                    subespecialidad s ON(s.id = c.idsubespecialidad) LEFT JOIN 
                    especialidad e ON(e.id = s.idespecialidad) LEFT JOIN
                    profesional p ON(p.id = c.idprofesional)
                WHERE
                    c.id = $idConsultorio ;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getHorario($idHorario)
    {
        $query="SELECT
                    ch.id,
                    ds.nombre,
                    ch.hora_desde,
                    ch.hora_hasta
                FROM
                    consultorio_horarios ch LEFT JOIN
                    dia_semana ds ON(ds.id=ch.iddia)
                WHERE
                    ch.id=$idHorario;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;

    }

}   