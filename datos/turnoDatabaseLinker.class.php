<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'profesional.class.php';
include_once datos.'utils.php';
require_once 'Spreadsheet/Excel/Writer.php';

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

    private function actualizarEstadoTurno($idTurno, $idEstado)
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

    function getTurnosAtendidosTodos($anio, $mes ,$page, $rows, $filters)
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
                    ta.detalle as tipo_atencion,
                    t.fecha,
                    t.hora,
                    s.detalle as subespecialidad,
                    concat(prof.nombre,' ',prof.apellido) as profesional,
                    et.detalle as estado,
                    td.detalle_corto AS tipodoc,
                    p.nrodoc,
                    p.nombre,
                    p.apellido,
                    p.sexo,
                    p.fecha_nacimiento,
                    vp.descripcion AS pais,
                    vpr.descripcion AS provincia,
                    vpa.descripcion AS partido,
                    vloc.descripcion AS localidad,
                    p.codigo_postal as codigo_postal,
                    p.calle_nombre AS calle,
                    p.calle_numero AS nro_calle,
                    p.piso AS piso,
                    p.departamento AS dpto,
                    p.telefono,
                    p.telefono2,
                    p.email,
                    IF(es_donante = '1', 'SI', 'NO') AS donante,
                    IFNULL((SELECT
                        o.detalle
                    FROM
                        paciente p2 LEFT JOIN
                        paciente_obra_social po ON(p2.tipodoc=po.tipodoc AND p2.nrodoc=po.nrodoc) LEFT JOIN
                        obra_social o ON(o.id=po.idobra_social)
                    WHERE
                        p2.nrodoc=p.nrodoc AND
                        p2.tipodoc=p.tipodoc
                    ORDER BY 
                        o.fecha_creacion ASC LIMIT 1),'SIN OBRA SOCIAL') as obra_social
                FROM
                    turno t 
                        LEFT JOIN
                    tipo_atencion ta ON(t.idtipo_atencion=ta.id)
                        LEFT JOIN
                    consultorio c ON (t.idconsultorio=c.id)
                        LEFT JOIN
                    subespecialidad s ON (c.idsubespecialidad=s.id)
                        LEFT JOIN
                    profesional prof ON (c.idprofesional=prof.id)
                        LEFT JOIN
                    estado_turno et ON (t.idestado_turno=et.id)
                        LEFT JOIN
                    paciente p ON (t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc)
                        LEFT JOIN
                    tipo_documento td ON (p.tipodoc = td.id)
                        LEFT JOIN
                    pais vp ON (p.idpais = vp.idresapro)
                        LEFT JOIN
                    provincia vpr ON (p.idprovincia = vpr.idresapro
                        AND p.idpais = vpr.idresapro_pais)
                        LEFT JOIN
                    partido vpa ON (p.idpartido = vpa.idresapro
                        AND p.idpais = vpa.idresapro_pais
                        AND p.idprovincia = vpa.idresapro_provincia)
                        LEFT JOIN
                    localidad vloc ON (p.idlocalidad = vloc.idresapro
                        AND p.idpais = vloc.idresapro_pais
                        AND p.idprovincia = vloc.idresapro_provincia
                        AND p.idpartido = vloc.idresapro_partido)
                WHERE
                    YEAR(t.fecha)=$anio AND
                    MONTH(t.fecha)=$mes 
                    ".$where."
                ORDER BY t.fecha ASC 
                LIMIT $rows OFFSET $offset;";
        try
        {
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e) 
        {
            throw new Exception("Error consultando las variables de la internacion", 1);              
        }

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);

            $at = array();
            $at['id'] = $result['id'];
            $at['tipo_atencion'] = $result['tipo_atencion'];
            $at['fecha'] = $result['fecha'];
            $at['hora'] = $result['hora'];
            $at['subespecialidad'] = $result['subespecialidad'];
            $at['profesional'] = $result['profesional'];
            $at['estado'] = $result['estado'];
            $at['tipodoc'] = $result['tipodoc'];
            $at['nrodoc'] = $result['nrodoc'];
            $at['nombre'] = $result['nombre'];
            $at['apellido'] = $result['apellido'];
            $at['sexo'] = $result['sexo'];
            $at['fecha_nacimiento'] = $result['fecha_nacimiento'];
            $at['pais'] = $result['pais'];
            $at['provincia'] = $result['provincia'];
            $at['partido'] = $result['partido'];
            $at['codigo_postal'] = $result['codigo_postal'];
            $at['calle'] = $result['calle']." ".$result['nro_calle'];
            $at['telefonos'] = $result['telefono']." / ".$result['telefono2'];
            $at['email'] = $result['email'];
            $at['obra_social'] = $result['obra_social'];

            $ret[] = $at;
        }

        return $ret;
    }

    private function getCantidadTurnosAtendidosTodos($anio, $mes ,$filters = null)
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

        $query="SELECT
                    count(*) as cantidad
                FROM
                    turno t 
                        LEFT JOIN
                    tipo_atencion ta ON(t.idtipo_atencion=ta.id)
                        LEFT JOIN
                    consultorio c ON (t.idconsultorio=c.id)
                        LEFT JOIN
                    subespecialidad s ON (c.idsubespecialidad=s.id)
                        LEFT JOIN
                    profesional prof ON (c.idprofesional=prof.id)
                        LEFT JOIN
                    estado_turno et ON (t.idestado_turno=et.id)
                        LEFT JOIN
                    paciente p ON (t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc)
                        LEFT JOIN
                    tipo_documento td ON (p.tipodoc = td.id)
                        LEFT JOIN
                    pais vp ON (p.idpais = vp.idresapro)
                        LEFT JOIN
                    provincia vpr ON (p.idprovincia = vpr.idresapro
                        AND p.idpais = vpr.idresapro_pais)
                        LEFT JOIN
                    partido vpa ON (p.idpartido = vpa.idresapro
                        AND p.idpais = vpa.idresapro_pais
                        AND p.idprovincia = vpa.idresapro_provincia)
                        LEFT JOIN
                    localidad vloc ON (p.idlocalidad = vloc.idresapro
                        AND p.idpais = vloc.idresapro_pais
                        AND p.idprovincia = vloc.idresapro_provincia
                        AND p.idpartido = vloc.idresapro_partido)
                WHERE
                    YEAR(t.fecha)=$anio AND
                    MONTH(t.fecha)=$mes 
                    ".$where.";";
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];

        return $ret;
    }

    function getTurnosAtendidosTodosJson($anio, $mes ,$page, $rows, $filters)
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['turnosExcelFilters'] = $filters;
        $_SESSION['turnosExcelMes'] = $mes;
        $_SESSION['turnosExcelAnio'] = $anio;

        $response = new stdClass();
        $this->dbTurnos->conectar();

        $turno_array = $this->getTurnosAtendidosTodos($anio, $mes ,$page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadTurnosAtendidosTodos($anio, $mes ,$filters) / $rows);
        $response->records = $this->getCantidadTurnosAtendidosTodos($anio, $mes ,$filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($turno_array) ; $i++) 
        {
            $turno = $turno_array[$i];
            //id de fila
            $response->rows[$i]['id'] = $turno['id']; 

            $row = array();
            $row['id'] = $turno['id'];
            $row['tipo_atencion'] = $turno['tipo_atencion'];
            $row['fecha'] = $turno['fecha'];
            $row['hora'] = $turno['hora'];
            $row['subespecialidad'] = $turno['subespecialidad'];
            $row['profesional'] = $turno['profesional'];
            $row['estado'] = $turno['estado'];
            $row['tipodoc'] = $turno['tipodoc'];
            $row['nrodoc'] = $turno['nrodoc'];
            $row['nombre'] = $turno['nombre'];
            $row['apellido'] = $turno['apellido'];
            $row['sexo'] = $turno['sexo'];
            $row['fecha_nacimiento'] = $turno['fecha_nacimiento'];
            $row['pais'] = $turno['pais'];
            $row['provincia'] = $turno['provincia'];
            $row['partido'] = $turno['partido'];
            $row['codigo_postal'] = $turno['codigo_postal'];
            $row['calle'] = $turno['calle'];
            $row['telefonos'] = $turno['telefonos'];
            $row['email'] = $turno['email'];
            $row['obra_social'] = $turno['obra_social'];
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['id'] = 'id';
        $response->userdata['tipo_atencion'] = 'tipo_atencion';
        $response->userdata['fecha'] = 'fecha';
        $response->userdata['hora'] = 'hora';
        $response->userdata['subespecialidad'] = 'subespecialidad';
        $response->userdata['profesional'] = 'profesional';
        $response->userdata['estado'] = 'estado';
        $response->userdata['tipodoc'] = 'tipodoc';
        $response->userdata['nrodoc'] = 'nrodoc';
        $response->userdata['nombre'] = 'nombre';
        $response->userdata['apellido'] = 'apellido';
        $response->userdata['sexo'] = 'sexo';
        $response->userdata['fecha_nacimiento'] = 'fecha_nacimiento';
        $response->userdata['pais'] = 'pais';
        $response->userdata['provincia'] = 'provincia';
        $response->userdata['partido'] = 'partido';
        $response->userdata['codigo_postal'] = 'codigo_postal';
        $response->userdata['calle'] = 'calle';
        $response->userdata['telefonos'] = 'telefonos';
        $response->userdata['email'] = 'email';
        $response->userdata['obra_social'] = 'obra_social';

        return json_encode($response);
    }

    function turnosToExcel()
    {
        session_start();

        $filters = $_SESSION['turnosExcelFilters'];
        $mes = $_SESSION['turnosExcelMes'];
        $anio = $_SESSION['turnosExcelAnio'];

        try
        {
            $this->dbTurnos->conectar();
            $turnos = $this->getTurnosAtendidosTodos($anio, $mes , 1, 9999999999999, $filters);
            $this->dbTurnos->desconectar();
        }
        catch (Exception $e)
        {
            throw new Exception("Error creando excel", 20123);
        }
        
        $hora = time();

        $filename = $hora . "_Turnos Atendidos.xls";
        
        $docExcel = new Spreadsheet_Excel_Writer(); 
        
        $nuevahoja =& $docExcel->addWorksheet("Turnos");
        
        $format =& $docExcel->addFormat(array('Size' => 10,
                                      'Align' => 'center',
                                      'Color' => 'black',
                                      'FgColor' => 'white',
                                      'Pattern' => 1,
                                      'Bold' => 1));

        $fila =0;

        $nuevahoja->write($fila, 0,'Nro',$format);
        $nuevahoja->write($fila, 1,'tipo_atencion',$format);
        $nuevahoja->write($fila, 2,'fecha',$format);
        $nuevahoja->write($fila, 3,'hora',$format);
        $nuevahoja->write($fila, 4,'subespecialidad',$format);
        $nuevahoja->write($fila, 5,'profesional',$format);
        $nuevahoja->write($fila, 6,'estado',$format);
        $nuevahoja->write($fila, 7,'tipodoc',$format);
        $nuevahoja->write($fila, 8,'nrodoc',$format);
        $nuevahoja->write($fila, 9,'nombre',$format);
        $nuevahoja->write($fila, 10,'apellido',$format);
        $nuevahoja->write($fila, 11,'sexo',$format);
        $nuevahoja->write($fila, 12,'fecha_nacimiento',$format);
        $nuevahoja->write($fila, 13,'pais',$format);
        $nuevahoja->write($fila, 14,'provincia',$format);
        $nuevahoja->write($fila, 15,'partido',$format);
        $nuevahoja->write($fila, 16,'codigo_postal',$format);
        $nuevahoja->write($fila, 17,'calle',$format);
        $nuevahoja->write($fila, 18,'telefonos',$format);
        $nuevahoja->write($fila, 19,'email',$format);
        $nuevahoja->write($fila, 20,'obra_social',$format);

        $fila=1;
        
        for ($i = 0; $i < count($turnos); $i++) 
        {
            $columna=0;

            $nuevahoja->write($fila, $columna, Utils::sqlIntToPHP($turnos[$i]['id']));
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['tipo_atencion']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['fecha']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['hora']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['subespecialidad']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['profesional']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['estado']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['tipodoc']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['nrodoc']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['nombre']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['apellido']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['sexo']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['fecha_nacimiento']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['pais']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['provincia']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['partido']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['codigo_postal']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['calle']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['telefonos']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['email']);
            $columna++;
            $nuevahoja->write($fila, $columna, $turnos[$i]['obra_social']);
            $columna++;

            $fila++;
        }
        
        $docExcel->send($filename);

        $docExcel->close();
    }
}