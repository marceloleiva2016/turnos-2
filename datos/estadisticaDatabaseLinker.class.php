<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'subespecialidadDatabaseLinker.class.php';
include_once datos.'especialidadDatabaseLinker.class.php';

class EstadisticaDatabaseLinker
{
    var $dbTurnos;

    function EstadisticaDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getServiciosConEdades($mes, $ano, $idsubespecialidad)
    {
        $query="SELECT
                    s.id as subespecialidad,
                    p.sexo,
                    YEAR(CURDATE())-YEAR(p.fecha_nacimiento) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fecha_nacimiento,'%m-%d'), 0, -1)  as edad
                FROM
                    turno t LEFT JOIN
                    turno_estado_log tl ON(tl.idturno = t.id AND tl.idestado_turno=3) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN 
                    subespecialidad s ON(c.idsubespecialidad = s.id) LEFT JOIN 
                    paciente p ON(p.nrodoc = t.nrodoc AND p.tipodoc = t.tipodoc)
                WHERE
                    month(tl.fecha_creacion) = $mes AND
                    year(tl.fecha_creacion) = $ano AND
                    c.idsubespecialidad = $idsubespecialidad;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los detalles para la hoja 2.1", 201230);
        }

        $menor1 = array();
            $menor1['M'] = 0;
            $menor1['F'] = 0;
        $de1a4 = array();
            $de1a4['M'] = 0;
            $de1a4['F'] = 0;
        $de5a9 = array();
            $de5a9['M'] = 0;
            $de5a9['F'] = 0;
        $de10a14 = array();
            $de10a14['M'] = 0;
            $de10a14['F'] = 0;
        $de15a19 = array();
            $de15a19['M'] = 0;
            $de15a19['F'] = 0;
        $de20a34 = array();
            $de20a34['M'] = 0;
            $de20a34['F'] = 0;
        $de35a49 = array();
            $de35a49['M'] = 0;
            $de35a49['F'] = 0;
        $de50a64 = array();
            $de50a64['M'] = 0;
            $de50a64['F'] = 0;
        $mas65 = array();
            $mas65['M'] = 0;
            $mas65['F'] = 0;

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);

            if($result['edad']<1)
                if($result['sexo']=='M') {
                    $menor1['M']++;
                } 
                else {
                    $menor1['F']++;
                }

            if($result['edad']>=1 AND $result['edad']<=4)
                if($result['sexo']=='M') {
                    $de1a4['M']++;
                }
                else {
                    $de1a4['F']++;
                }
                    
            if($result['edad']>=5 AND $result['edad']<=9)
                if($result['sexo']=='M') {
                    $de5a9['M']++;
                }
                else {
                    $de5a9['F']++;
                }
                    
            if($result['edad']>=10 AND $result['edad']<=14)
                if($result['sexo']=='M') {
                    $de10a14['M']++;
                }
                else {
                    $de10a14['F']++;
                }
                    
            if($result['edad']>=15 AND $result['edad']<=19)
                if($result['sexo']=='M') {
                    $de15a19['M']++;
                }
                else {
                    $de15a19['F']++;
                }
                    
            if($result['edad']>=20 AND $result['edad']<=34)
                if($result['sexo']=='M') {
                    $de20a34['M']++;
                }
                else {
                    $de20a34['F']++;
                }
                    
            if($result['edad']>=35 AND $result['edad']<=49)
                if($result['sexo']=='M') {
                    $de35a49['M']++;
                }
                else {
                    $de35a49['F']++;
                }
                    
            if($result['edad']>=50 AND $result['edad']<=64)
                if($result['sexo']=='M') {
                    $de50a64['M']++;
                }
                else {
                    $de50a64['F']++;
                }
                    
            if($result['edad']>=65)
                if($result['sexo']=='M') {
                    $mas65['M']++;
                }
                else {
                    $mas65['F']++;
                }
                    
        }

        $this->dbTurnos->desconectar();

        $ret = array();

        $ret[] = $menor1;
        $ret[] = $de1a4;
        $ret[] = $de5a9;
        $ret[] = $de10a14;
        $ret[] = $de15a19;
        $ret[] = $de20a34;
        $ret[] = $de35a49;
        $ret[] = $de50a64;
        $ret[] = $mas65;

        return $ret;            
    }

    function especialidadesConSexosyRangos($mes,$ano)
    {
        $dbSubesp= new SubespecialidadDatabaseLinker();
        $dbEsp = new EspecialidadDatabaseLinker();

        $subespecialidades = $dbSubesp->getSubespecialidades();

        $lista = array();

        for ($i=0; $i < count($subespecialidades); $i++)//voy agregando lista y especialidades
        {
            $especi = array();

            $list = $this->getServiciosConEdades($mes, $ano, $subespecialidades[$i]->getId());

            $especi['especialidad'] = $dbEsp->getEspecialidad($subespecialidades[$i]->getEspecialidad())->getDetalle();
            $especi['nombre'] = $subespecialidades[$i]->getDetalle();
            $especi['lista'] = $list;
            $contador = 0;

            for ($x=0; $x < count($list); $x++) {
                $contador+=$list[$x]['M'];
                $contador+=$list[$x]['F'];
            }

            $especi['cantidad'] = $contador;

            $lista[] = $especi;
        }

        return $lista;
    }

    function turnosAntendidosYporAntenderDemanda($fecha)
    {

        $query="SELECT 
                    CONCAT(pf.nombre,'',pf.apellido) as profesional,
                    s.detalle as subespecialidad,
                    p.nombre,
                    p.apellido,
                    (select 
                            es.detalle
                        from
                            turno_estado_log te
                                LEFT JOIN
                            estado_turno es ON (es.id = te.idestado_turno)
                        WHERE
                            te.idturno = t.id
                        order by te.fecha_creacion desc
                        limit 1) as estado,
                    t.fecha_creacion
                FROM
                    turno t
                        LEFT JOIN
                    consultorio c ON (t.idconsultorio = c.id)
                        LEFT JOIN
                    subespecialidad s ON (c.idsubespecialidad = s.id)
                        LEFT JOIN
                    profesional pf ON (c.idprofesional = pf.id)
                        LEFT JOIN
                    paciente p ON (t.nrodoc = p.nrodoc
                        AND t.tipodoc = p.tipodoc)
                WHERE
                    DATE(t.fecha_creacion) = ".Utils::phpTimestampToSQLDate($fecha)." AND
                    c.idtipo_consultorio=1
                order by t.fecha_creacion asc;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los detalles para la hoja 2.1", 201230);
        }

        $array = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $array[] = $result;

        }

        return $array;
    }

    function turnosAntendidosYporAntenderProgramado($fecha)
    {

        $query="SELECT 
                    CONCAT(pf.nombre,'',pf.apellido) as profesional,
                    s.detalle as subespecialidad,
                    p.nombre,
                    p.apellido,
                    (select 
                            es.detalle
                        from
                            turno_estado_log te
                                LEFT JOIN
                            estado_turno es ON (es.id = te.idestado_turno)
                        WHERE
                            te.idturno = t.id
                        order by te.fecha_creacion desc
                        limit 1) as estado,
                    t.fecha_creacion
                FROM
                    turno t
                        LEFT JOIN
                    consultorio c ON (t.idconsultorio = c.id)
                        LEFT JOIN
                    subespecialidad s ON (c.idsubespecialidad = s.id)
                        LEFT JOIN
                    profesional pf ON (c.idprofesional = pf.id)
                        LEFT JOIN
                    paciente p ON (t.nrodoc = p.nrodoc
                        AND t.tipodoc = p.tipodoc)
                WHERE
                    DATE(t.fecha_creacion) = ".Utils::phpTimestampToSQLDate($fecha)." AND
                    c.idtipo_consultorio=2
                order by t.fecha_creacion asc;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los detalles para la hoja 2.1", 201230);
        }

        $array = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $array[] = $result;

        }

        return $array;
    }
}