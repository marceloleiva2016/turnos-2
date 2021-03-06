<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once 'feriadoDatabaseLinker.class.php';

class ConsultorioDatabaseLinker
{
    var $dbTurnos;
    var $dbFeriados;

    function ConsultorioDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        $this->dbFeriados = new FeriadoDatabaseLinker();
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
                    ".$feriados.", 
                    '".$fecha_inicio."',
                    ".$fecha_fin.",
                    now(),
                    ".Utils::phpIntToSQL($usuario).",
                    true
                    );";
        

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            echo $e;
            $this->dbTurnos->desconectar();
            return false;
        }

        $id = $this->dbTurnos->ultimoIdInsertado();

        $this->dbTurnos->desconectar();

        return $id;
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
        $this->dbTurnos->desconectar();

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

        $this->dbTurnos->desconectar();

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
            $this->dbTurnos->conectar();//3
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar el tipo de consultorio", 201230);
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();//3

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

    function getProfesionalesEnSubespecialidadConConsultorioActivo($idsubespecialidad)
    {
        $query="SELECT
                    c.idprofesional as id,
                    concat(p.nombre,' ', p.apellido) as detalle
                FROM
                    consultorio c LEFT JOIN
                    profesional p ON(p.id = c.idprofesional)
                WHERE
                    c.idsubespecialidad = $idsubespecialidad AND
                    c.idtipo_consultorio=2 AND
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
            throw new Exception("No se pudo consultar la consulta", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ret[] = $this->dbTurnos->fetchRow($query);
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getProfesionalesEnSubespecialidadConConsultorioActivo_json($idsub)
    {
        $std = new stdClass();

        $profesionales = $this->getProfesionalesEnSubespecialidadConConsultorioActivo($idsub);

        $std->ret=false;

        $std->datos= "";

        if($profesionales!=false)
        {    
            $std->ret = true;

            $std->datos = $profesionales;
        }

        return $std;
    }

    function getConsultorioPorDatos($idsubespecialidad, $idprofesional)
    {
        $query="SELECT
                    id,
                    idsubespecialidad,
                    idprofesional,
                    dias_anticipacion,
                    duracion_turno,
                    feriados,
                    fecha_inicio,
                    fecha_fin
                FROM
                    consultorio
                WHERE
                    idsubespecialidad=$idsubespecialidad AND
                    idprofesional=$idprofesional AND
                    idtipo_consultorio=2 AND
                    habilitado=true;";

        try {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        } catch (Exception $e) {
            $this->dbTurnos->desconectar();
            return false;
        }

        $ret = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getDiasEnConsultorio($idConsultorio)
    {
        $query="SELECT
                    ch.iddia,
                    ds.nombre
                FROM
                    consultorio_horarios ch LEFT JOIN
                    dia_semana ds ON(ch.iddia=ds.id)
                WHERE
                    ch.idconsultorio=$idConsultorio AND
                    ch.habilitado=true
                GROUP BY ch.iddia;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar la consulta", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $ret[$result['iddia']] = $result['nombre'];
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getHorariosEnDiaEnConsultorio($idConsultorio, $iddia)
    {
        $query="SELECT
                    ch.hora_desde as desde,
                    ch.hora_hasta as hasta
                FROM
                    consultorio_horarios ch LEFT JOIN
                    dia_semana ds ON(ch.iddia=ds.id)
                WHERE
                    ch.idconsultorio=$idConsultorio AND
                    ch.iddia=$iddia AND
                    ch.habilitado=true
                ORDER BY
                    ch.hora_desde ASC;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar la consulta", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $ret[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getFechasConsultorio($idsubespecialidad, $idprofesional)
    {
        $consultorio = $this->getConsultorioPorDatos($idsubespecialidad, $idprofesional);

        $cantiDias = $consultorio['dias_anticipacion'];

        $diasSemanaConsultorio = $this->getDiasEnConsultorio($consultorio['id']);

        $fechasPorSemana = array();

        $fecha = date('Y-m-j');

        $ret = array();

        $ret[1] = array();//Lunes
        $ret[2] = array();//Martes
        $ret[3] = array();//Miercoles
        $ret[4] = array();//Jueves
        $ret[5] = array();//Viernes
        $ret[6] = array();//Sabado
        $ret[7] = array();//Domingo

        $nroSemana = date('W', strtotime($fecha));

        for ($i=0; $i < $cantiDias; $i++)
        {
            if($nroSemana!=date('W', strtotime($fecha)))
            {   
                //Agrego el array previamente llenado como una semana
                $fechasPorSemana[] = $ret;

                //Asigno un nuevo nro de semana
                $nroSemana = date('W', strtotime($fecha));

                //Vacio array lleno para crear uno nuevo
                $ret = array();
                $ret[1] = array();//Lunes
                $ret[2] = array();//Martes
                $ret[3] = array();//Miercoles
                $ret[4] = array();//Jueves
                $ret[5] = array();//Viernes
                $ret[6] = array();//Sabado
                $ret[7] = array();//Domingo
            }

            $nroDia = date('N', strtotime($fecha));

            //
            if(array_key_exists($nroDia, $diasSemanaConsultorio))
            {
                if($this->dbFeriados->existeFeriado($fecha))
                {
                    if($consultorio['feriados']=="1")
                    {
                        if(!$this->dbFeriados->existeVacacion($fecha, $idprofesional))
                        {
                            $tupla = array();
                            $tupla['iddia'] = $nroDia;
                            $tupla['dia'] = $diasSemanaConsultorio[$nroDia];
                            $tupla['fecha'] = $fecha;

                            $ret[$tupla['iddia']][] = $tupla;
                        }
                    }
                }
                else
                {
                    if(!$this->dbFeriados->existeVacacion($fecha, $idprofesional))
                    {
                        $tupla = array();
                        $tupla['iddia'] = $nroDia;
                        $tupla['dia'] = $diasSemanaConsultorio[$nroDia];
                        $tupla['fecha'] = $fecha;

                        $ret[$tupla['iddia']][] = $tupla;
                    }
                }
            }

            $fecha = strtotime('+1 day', strtotime($fecha));

            $fecha = date('Y-m-j',$fecha);
        }

        return $fechasPorSemana;
    }

    function getTurnosAsignadosEnFecha($idConsultorio, $fecha)
    {
        $query="SELECT
                    t.hora,
                    concat(p.nombre,' ',p.apellido) as paciente
                FROM
                    turno t LEFT JOIN
                    paciente p ON (t.tipodoc = p.tipodoc AND t.nrodoc = p.nrodoc)
                WHERE
                    t.idconsultorio=$idConsultorio AND
                    t.fecha='".$fecha."' AND
                    t.idestado_turno=1;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return false;
            throw new Exception("No se pudo consultar la consulta", 201230);
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $ret[$result['hora']] = $result['paciente'];
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getHorariosEnFechaConsultorio($idsubespecialidad, $idprofesional, $fecha, $iddia)
    {
        $consultorio = $this->getConsultorioPorDatos($idsubespecialidad, $idprofesional);

        $horariosGen = $this->getHorariosEnDiaEnConsultorio($consultorio['id'], $iddia);

        $turnosYaAsignados = $this->getTurnosAsignadosEnFecha($consultorio['id'],$fecha);

        $minutoAnadir = $consultorio['duracion_turno'];

        $horarios = array();

        for ($i=0; $i < count($horariosGen); $i++) {

            $desde = date('H:i:s', Utils::sqlTimeToPHPTimestamp($horariosGen[$i]['desde']));

            $hasta = date('H:i:s', Utils::sqlTimeToPHPTimestamp($horariosGen[$i]['hasta']));

            while(strtotime($desde)<strtotime($hasta)) {
                $tupla = array();
                $tupla['hora'] = $desde;

                if(array_key_exists($desde, $turnosYaAsignados)) {
                    $tupla['paciente']  = $turnosYaAsignados[$desde];
                } else {
                    $tupla['paciente']  = null; 
                }

                $horarios[] = $tupla;

                $desde = date('H:i:s', strtotime('+'.$minutoAnadir.' minutes', strtotime($desde)));
            }
        }

        return $horarios;
    }

    function borrarConsultorio($idconsultorio)
    {
        $query="UPDATE
                    consultorio
                SET
                    habilitado=false
                WHERE
                    id=$idconsultorio;";
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

    function getConsultoriosConFiltro($detalle) 
    {
        $query="SELECT 
                    c.id,
                    tc.detalle as tipo_consultorio,
                    e.detalle as especialidad,
                    s.detalle as subespecialidad,
                    concat(p.nombre,' ',p.apellido) as profesional
                FROM
                    consultorio c LEFT JOIN
                    tipo_consultorio tc ON(c.idtipo_consultorio=tc.id) LEFT JOIN
                    subespecialidad s ON(c.idsubespecialidad=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    profesional p ON(c.idprofesional=p.id)
                WHERE
                    (tc.detalle like '%".$detalle."%' OR
                    s.detalle like '%".$detalle."%' OR
                    e.detalle like '%".$detalle."%' OR
                    concat(p.nombre,' ',p.apellido) like '%".$detalle."%') AND
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
}   