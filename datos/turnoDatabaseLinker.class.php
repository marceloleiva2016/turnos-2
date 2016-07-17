<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'profesional.class.php';
include_once datos.'utils.php';

class TurnoDatabaseLinker
{
    var $dbTurnos;

    function TurnoDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function crearTurnoDemanda($arrayTurno,$idConsultorio, $idtipo_atencion)
    {
        $query="INSERT INTO
                    turno(
                        tipodoc,
                        nrodoc,
                        idestado_turno,
                        idconsultorio,
                        idtipo_atencion,
                        fecha,
                        hora,
                        fecha_creacion,
                        idusuario)
                VALUES (
                        '".$arrayTurno['tipoDoc']."',
                        '".$arrayTurno['nroDoc']."',
                        '2',
                        '".$idConsultorio."',
                        '".$idtipo_atencion."',
                        date(now()),
                        time(now()),
                        now(),
                        '".$arrayTurno['idusuario']."'
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

        try
        {
            $idTurno = $this->dbTurnos->ultimoIdInsertado();
            $this->insertarEnLog($idTurno, 2, $arrayTurno['idusuario']);   
        }
        catch (Exception $e)
        {
            throw new Exception("Error insertando en el log del turno", 1);
        }

        $this->dbTurnos->desconectar();

        return true;
    }

    function crearTurnoProgramado($arrayTurno,$idConsultorio, $idtipo_atencion)
    {
        $query="INSERT INTO
                    turno(
                        tipodoc,
                        nrodoc,
                        idestado_turno,
                        idconsultorio,
                        idtipo_atencion,
                        fecha,
                        hora,
                        fecha_creacion,
                        idusuario)
                VALUES (
                        '".$arrayTurno['tipoDoc']."',
                        '".$arrayTurno['nroDoc']."',
                        '1',
                        '".$idConsultorio."',
                        '".$idtipo_atencion."',
                        '".$arrayTurno['fecha']."',
                        '".$arrayTurno['hora']."',
                        now(),
                        '".$arrayTurno['idusuario']."'
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

        try
        {
            $idTurno = $this->dbTurnos->ultimoIdInsertado();
            $this->insertarEnLog($idTurno, 1, $arrayTurno['idusuario']);   
        }
        catch (Exception $e)
        {
            throw new Exception("Error insertando en el log del turno", 1);
        }

        $this->dbTurnos->desconectar();

        return true;
    }

    function getTurnosConfirmadosDemanda($idsubespecialidad)
    {
        $this->dbTurnos->conectar();

        $query="SELECT
                    t.id,
                    td.detalle_corto as tipodoc,
                    t.nrodoc,
                    p.nombre,
                    p.apellido,
                    ct.detalle,
                    t.fecha_creacion
                FROM
                    turno t LEFT JOIN
                    paciente p ON(t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    tipo_consultorio ct ON(c.idtipo_consultorio=ct.id) LEFT JOIN    
                    tipo_documento  td ON(t.tipodoc=td.id)
                WHERE 
                    t.idestado_turno = 2 AND
                    c.idsubespecialidad = $idsubespecialidad AND
                    c.idtipo_consultorio = 1
                ORDER BY t.fecha_creacion ASC;";

        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $turno = array();

            $result = $this->dbTurnos->fetchRow($query);

            $turno['id'] = $result['id'];
            $turno['tipodoc'] = $result['tipodoc'];
            $turno['nrodoc'] = $result['nrodoc'];
            $turno['nombre'] = $result['nombre']." ".$result['apellido'];
            $turno['tipo_turno'] = $result['detalle'];
            $turno['fecha'] = Utils::sqlDateTimeToHtmlDateTime($result['fecha_creacion']);
            $ret[] = $turno;
        }

        return $ret;
    }
	
    function getTurnosConfirmadosConsultorio($idsubespecialidad, $idprofesional)
    {
    	$this->dbTurnos->conectar();
    
    	$query="SELECT
			    	t.id,
			    	td.detalle_corto as tipodoc,
			    	t.nrodoc,
			    	p.nombre,
			    	p.apellido,
			    	ct.detalle,
			    	t.fecha_creacion
		    	FROM
			    	turno t LEFT JOIN
			    	paciente p ON(t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc) LEFT JOIN
			    	consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
			    	tipo_consultorio ct ON(c.idtipo_consultorio=ct.id) LEFT JOIN
			    	tipo_documento  td ON(t.tipodoc=td.id)
		    	WHERE
			    	t.idestado_turno = 2 AND
			    	c.idsubespecialidad = $idsubespecialidad AND
			    	c.idtipo_consultorio = 2 AND
                    c.idprofesional = $idprofesional
			    	ORDER BY t.fecha_creacion ASC;";
    
    	$this->dbTurnos->ejecutarQuery($query);
    
    	$ret = array();
    
    	for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
    	{
    		$turno = array();
    
    		$result = $this->dbTurnos->fetchRow($query);
    
    		$turno['id'] = $result['id'];
    		$turno['tipodoc'] = $result['tipodoc'];
    		$turno['nrodoc'] = $result['nrodoc'];
    		$turno['nombre'] = $result['nombre']." ".$result['apellido'];
    		$turno['tipo_turno'] = $result['detalle'];
    		$turno['fecha'] = Utils::sqlDateTimeToHtmlDateTime($result['fecha_creacion']);
    		$ret[] = $turno;
    	}
    
    	return $ret;
    }
    
    function getTurnosConfirmados($page, $rows, $filters)
    {

        $where = "";
        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.=" ".$filters['rules'][$i]['field']." like '".$filters['rules'][$i]['data']."%'";
            }
        }

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    t.id,
                    td.detalle_corto as tipodoc,
                    t.nrodoc,
                    p.nombre,
                    p.apellido,
                    ct.detalle,
                    t.fecha_creacion
                FROM
                    turno t LEFT JOIN
                    paciente p ON(t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    tipo_consultorio ct ON(c.idtipo_consultorio=ct.id) LEFT JOIN    
                    tipo_documento  td ON(t.tipodoc=td.id)
                WHERE 
                    t.idestado_turno = 2 
                    ".$where."
                ORDER BY t.fecha_creacion ASC 
                LIMIT $rows OFFSET $offset;";

        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $turno = array();

            $result = $this->dbTurnos->fetchRow($query);

            $turno['id'] = $result['id'];
            $turno['tipodoc'] = $result['tipodoc'];
            $turno['nrodoc'] = $result['nrodoc'];
            $turno['nombre'] = $result['nombre']." ".$result['apellido'];
            $turno['tipo_turno'] = $result['detalle'];
            $turno['fecha'] = Utils::sqlDateTimeToHtmlDateTime($result['fecha_creacion']);
            $ret[] = $turno;
        }

        return $ret;
    }

    private function getCantidadTurnosConfirmados($filters = null)
    {

        $where = " ";

        $query="SELECT
                    COUNT(*) AS cantidad
                FROM
                    turno t LEFT JOIN
                    paciente p ON(t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    tipo_consultorio ct ON(c.idtipo_consultorio=ct.id)
                WHERE 
                    t.idestado_turno = 2 ";
        $query .= " " . $where;
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];

        return $ret;
    }

    function getTurnosConfirmadosJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $turnos_array = $this->getTurnosConfirmados($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadTurnosConfirmados($filters) / $rows);
        $response->records = $this->getCantidadTurnosConfirmados($filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($turnos_array) ; $i++) 
        {
            $turnos = $turnos_array[$i];
            //id de fila
            $response->rows[$i]['id'] = $turnos['id']; 
            $row = array();
           
            $row[] = $turnos['tipodoc'];
            $row[] = $turnos['nrodoc'];
            $row[] = $turnos['nombre'];
            $row[] = $turnos['tipo_turno'];
            $row[] = $turnos['fecha'];
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['tipodoc']= 'Tipodoc';
        $response->userdata['nrodoc']= 'Nrodoc';
        $response->userdata['nombre']= 'Nombre';
        $response->userdata['tipo_turno']= 'tipo_turno';
        $response->userdata['fecha']= 'fecha';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function obtenerVariablesTurno($idTurno)
    {
        $query="SELECT
                    t.tipodoc as tipodoc,
                    t.nrodoc as nrodoc,
                    c.idprofesional as idprofesional,
                    c.idsubespecialidad as idsubespecialidad,
                    t.idtipo_atencion as idtipo_atencion
                FROM
                    turno t join 
                    consultorio c on (t.idconsultorio = c.id)
                WHERE
                    t.id=$idTurno;";
                    
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

    function insertarEnLog($idTurno, $idEstado, $iduser)
    {
        $query="INSERT INTO
                    turno_estado_log
                        (`idturno`,
                        `idestado_turno`,
                        `fecha_creacion`,
                        `iduser`)
                VALUES (
                        $idTurno,
                        $idEstado,
                        now(),
                        $iduser
                        );";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
        }
        catch (Exception $e)
        {
            throw new Exception("Error insertando en el log del turno", 1);
        }

        $this->actualizarEstadoTurno($idTurno, $idEstado);

        $this->dbTurnos->desconectar();

        return true;
    }

    function actualizarEstadoTurno($idTurno, $idEstado)
    {
        $query="UPDATE
                    turno
                SET 
                    `idestado_turno`=$idEstado
                WHERE
                    id=$idTurno;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error insertando en el log del turno", 1);
        }

        $this->dbTurnos->desconectar();

        return true;
    }

    function getTurnoAsignadoDePaciente($tipodoc, $nrodoc)
    {
        $query="SELECT
                    t.id as id,
                    e.detalle as especialidad,
                    s.detalle as subespecialidad,
                    concat(p.nombre,' ',p.apellido) as profesional,
                    t.fecha,
                    t.hora
                FROM
                    turno t LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    subespecialidad s ON(c.idsubespecialidad=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    profesional p ON(p.id=c.idprofesional)
                WHERE
                    t.tipodoc=$tipodoc AND
                    t.nrodoc=$nrodoc AND
                    t.idtipo_atencion=2 AND
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