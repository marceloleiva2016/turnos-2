<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'consultorioDatabaseLinker.class.php';
include_once negocio.'turnero.class.php';

class TurneroDatabaseLinker
{
    var $dbTurnos;
    var $dbConsultorios;

    function TurneroDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        $this->dbConsultorios = new ConsultorioDatabaselinker();
    }

    function insertarLlamado($idturno, $idusuario)
    {
        $ret = new stdClass();

        $query="INSERT INTO
                    turnero_llamado (
                        `idturno`,
                        `fecha_creacion`,
                        `idusuario`,
                        `habilitado`)
                VALUES (
                    $idturno,
                    now(),
                    $idusuario,
                    true);";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $ret->message = "Ocurrio un error al cargar el llamado.";
            $ret->result = false;
        }

        $this->dbTurnos->desconectar();

        $ret->message = "Paciente llamado.";
        $ret->result = true;

        return $ret;
    }

    function puedeLlamar($idturno)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM
                    turnero_llamado
                WHERE
                    idturno=$idturno AND
                    habilitado=true AND
                    fecha_creacion between DATE_SUB(now(), interval 10 minute) and now()
                ORDER BY fecha_creacion DESC
                LIMIT 1;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['cantidad']=="0";
    }

    function crearTurnero($idusuario)
    {
        $query="INSERT INTO
                    turnero(
                        `fecha_creacion`,
                        `habilitado`,
                        `idusuario`)
                VALUES(
                    now(),
                    true,
                    ".$idusuario.");";
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

        $idPantalla = $this->dbTurnos->ultimoIdInsertado();

        $this->dbTurnos->desconectar();

        return $idPantalla;
    }

    function insertarItem($idturnero, $idconsultorio)
    {
        $query="INSERT INTO
                    turnero_consultorio(
                        `idturnero`,
                        `idconsultorio`,
                        `fecha_creacion`)
                VALUES (
                    ".$idturnero.",
                    ".$idconsultorio.",
                    now());";

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

    function getConsultoriosTurnero($idturnero)
    {
        $ret = array();

        $query="SELECT
                    idconsultorio
                FROM
                    turnero_consultorio
                WHERE
                    idturnero=".$idturnero." AND 
                    habilitado = true
                ORDER BY 
                    idconsultorio ASC;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return $ret;
        }

        for ($t = 0; $t < $this->dbTurnos->querySize(); $t++)
        {
            $result = $this->dbTurnos->fetchRow();
            $consultorioi = $this->dbConsultorios->getConsultorio($result['idconsultorio']);//3

            $consultorio = new Consultorio();
            $consultorio->setId($result['idconsultorio']);
            $consultorio->setEspecialidad($consultorioi['especialidad']);
            $consultorio->setSubespecialidad($consultorioi['subespecialidad']);
            $consultorio->setProfesional($consultorioi['profesional']);
            $consultorio->setTipo_consultorio($consultorioi['tipo_consultorio']);
            $ret[] = $consultorio;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getTurneros($page, $rows, $filters)
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
                    id,
                    fecha_creacion,
                    idusuario
                FROM
                    turnero
                WHERE
                    habilitado=true ".$where."
                LIMIT $rows OFFSET $offset;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $ret = array();

        $ListaTurneros = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $ListaTurneros[] = $this->dbTurnos->fetchRow($query);
        }

        for ($f = 0; $f < count($ListaTurneros); $f++)
        {
            $turnero = new Turnero();
            $turnero->setId($ListaTurneros[$f]['id']);
            $turnero->setFechaCreacion($ListaTurneros[$f]['fecha_creacion']);
            $turnero->setIdUsuario($ListaTurneros[$f]['idusuario']);

            $consultorios = $this->getConsultoriosTurnero($turnero->getId());

            $turnero->setConsultorios($consultorios);

            $ret[] = $turnero;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getCantidadTurneros($filters = null)
    {
        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    turnero
                WHERE
                    habilitado=true;";
        
        $this->dbTurnos->conectar();
        $this->dbTurnos->ejecutarQuery($query);

        $result = $this->dbTurnos->fetchRow($query);

        $ret = $result['cantidad'];

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getTurnerosJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $subarray = $this->getTurneros($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadTurneros($filters) / $rows);
        $response->records = $this->getCantidadTurneros($filters);

        for ($i=0; $i < count($subarray) ; $i++) 
        {
            $consultorios = $subarray[$i]->getConsultorios();

            $stringConsutorios = " ";

            for ($l=0; $l < count($consultorios); $l++) {
                $stringConsutorios.=" ".$consultorios[$l]->getId()." ";

                if($l==count($consultorios)-1){
                    $stringConsutorios.=". ";
                } else {
                    $stringConsutorios.=", ";
                }
            }

            //id de fila
            $response->rows[$i]['id'] = $subarray[$i]->getId();
            $row = array();
            $row[] = $subarray[$i]->getId();
            $row[] = $stringConsutorios;
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Id']= 'Id';
        $response->userdata['consultorios']= 'consultorios';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function eliminarTurneros($id, $idusuario)
    {
        $query="UPDATE
                    turnero
                SET
                    habilitado = false,
                    idusuario = $idusuario
                WHERE
                    id = $id;";
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

    function getConsultoriosEnTurnero2($idturnero, $page, $rows)
    {
        $offset = ($page - 1) * $rows;

        $query="SELECT
                    id,
                    idconsultorio
                FROM
                    turnero_consultorio
                WHERE
                    idturnero=".$idturnero." AND  
                    habilitado = true 
                ORDER BY idconsultorio ASC
                LIMIT $rows OFFSET $offset;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return $ret;
        }

        for ($t = 0; $t < $this->dbTurnos->querySize(); $t++)
        {
            $result = $this->dbTurnos->fetchRow();
            $consultorioi = $this->dbConsultorios->getConsultorio($result['idconsultorio']);

            $consultorio = new Consultorio();
            $consultorio->setId($result['idconsultorio']);
            $consultorio->setEspecialidad($consultorioi['especialidad']);
            $consultorio->setSubespecialidad($consultorioi['subespecialidad']);
            $consultorio->setProfesional($consultorioi['profesional']);
            $consultorio->setTipo_consultorio($consultorioi['tipo_consultorio']);
            $ret[] = $consultorio;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getCantidadConsultoriosEnTurnero($idturnero)
    {
        $query="SELECT
                    count(*) AS cantidad
                FROM
                    turnero_consultorio
                WHERE
                    idturnero=".$idturnero.";";
        
        $this->dbTurnos->conectar();
        $this->dbTurnos->ejecutarQuery($query);

        $result = $this->dbTurnos->fetchRow($query);

        $ret = $result['cantidad'];

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getConsultoriosEnTurneroJson($idturnero, $page, $rows)
    {
        $response = new stdClass();
        $subarray = $this->getConsultoriosEnTurnero2($idturnero, $page, $rows);

        $response->page = $page;
        $response->total = ceil($this->getCantidadConsultoriosEnTurnero($idturnero) / $rows);
        $response->records = $this->getCantidadConsultoriosEnTurnero($idturnero);

        for ($i=0; $i < count($subarray) ; $i++)
        {
            $consultorio = $subarray[$i];

            //id de fila
            $response->rows[$i]['id'] = $consultorio->getId();
            $row = array();
            $row[] = $consultorio->getId();
            $row[] = $consultorio->getTipo_consultorio();
            $row[] = $consultorio->getProfesional();
            $row[] = $consultorio->getSubespecialidad();
            $row[] = $consultorio->getEspecialidad();
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Id']= 'Id';
        $response->userdata['Tipo Consultorio']= 'Tipo Consultorio';
        $response->userdata['Profesional']= 'Profesional';
        $response->userdata['Subespecialidad']= 'Subespecialidad';
        $response->userdata['Especialidad']= 'Especialidad';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function insertarConsultorioTurnero($idturnero, $idconsultorio, $idusuario)
    {
        $response = new stdClass();

        $existe = $this->existeConsultorioEnTurnero($idturnero, $idconsultorio);
        if($existe)
        {
            $response->message = "Ya existe el consultorio en el tunero!";
            $response->result = true;
        }
        else
        {
            $query="INSERT INTO 
                        turnero_consultorio(
                            `idturnero`,
                            `idconsultorio`,
                            `fecha_creacion`,
                            `idusuario`,
                            `habilitado`)
                    VALUES (
                            $idturnero,
                            $idconsultorio,
                            now(),
                            $idusuario,
                            true
                            );";

            try
            {
                $this->dbTurnos->conectar();
                $this->dbTurnos->ejecutarAccion($query);    
                $response->message = "Consultorio agregado al turnero";
                $response->result = true;
            }
            catch (Exception $e)
            {
                $this->dbTurnos->desconectar();
                $response->message = "Ocurrio un error al relacionar el consultorio con el turnero";
                $response->result = false;
            }

            $this->dbTurnos->desconectar();
        }

        return $response;
    }

    function existeConsultorioEnTurnero($idturnero, $idconsultorio)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM 
                    turnero_consultorio
                WHERE
                    idturnero = $idturnero AND
                    idconsultorio = $idconsultorio AND
                    habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }
        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['cantidad']!="0";
    }

    function existeConsultorioEnTurnero2($idsubespecialidad, $idprofesional)
    {
        $where = "WHERE tc.habilitado=true AND c.idsubespecialidad = $idsubespecialidad";

        if($idprofesional==''){
            $where.= ";";
        } else {
            $where.= " AND c.idprofesional=$idprofesional;";
        }

        $query="SELECT
                    tc.id,
                    c.idsubespecialidad,
                    c.idprofesional,
                    c.idtipo_consultorio
                FROM
                    turnero_consultorio tc LEFT JOIN
                    consultorio c ON(tc.idconsultorio = c.id) "
                .$where;

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el tunero con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result!=null;
    }

    function quitarConsultorioDeTurnero($idturnero, $idconsultorio, $idusuario)
    {
        $response = new stdClass();
        $query="UPDATE
                    turnero_consultorio
                SET
                    habilitado=false,
                    idusuario=$idusuario
                WHERE
                    idconsultorio=$idconsultorio AND
                    idturnero=$idturnero AND
                    habilitado=true;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "Consultorio quitado del turnero";
            $response->result = true;
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            $response->message = "Ocurrio un error al quitar el consultorio del turnero";
            $response->result = false;
        }

        $this->dbTurnos->desconectar();

        return $response;
    }

    function getTurnero($idturnero)
    {
        $query="SELECT
                    id,
                    fecha_creacion,
                    idusuario
                FROM
                    turnero
                WHERE
                    habilitado=true AND
                    id=$idturnero ;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error Processing Request", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $turnero = new Turnero();
        $turnero->setId($idturnero);
        $turnero->setFechaCreacion($result['fecha_creacion']);
        $turnero->setIdUsuario($result['idusuario']);

        $consultorios = $this->getConsultoriosTurnero($turnero->getId());

        $turnero->setConsultorios($consultorios);

        $this->dbTurnos->desconectar();

        return $turnero;
    }

    function getLlamadosTurnosEnConsultorios(array $consultorios)
    {
        $where = "";

        if(count($consultorios)>0)
        {
            $where.="(";

            for($i=0; $i < count($consultorios); $i++ )
            {
                $where.=" c.id=".$consultorios[$i]->getId();

                if($i!=count($consultorios)-1){
                    $where.=" OR ";
                }
            }

            $where.=")";
        }

        $query="SELECT
                    tl.id as id,
                    concat(p.nombre,' ',p.apellido) as paciente,
                    e.detalle as especialidad,
                    s.detalle as subespecialidad,
                    concat(prof.nombre,' ',prof.apellido) as profesional,
                    time(tl.fecha_creacion) as hora_llamado,
                    t.idestado_turno as estado_turno
                FROM
                    turnero_llamado tl LEFT JOIN
                    turno t ON(tl.idturno = t.id) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    paciente p ON(t.tipodoc = p.tipodoc AND t.nrodoc = p.nrodoc) LEFT JOIN
                    subespecialidad s ON(c.idsubespecialidad=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    profesional prof ON(c.idprofesional=prof.id)
                WHERE
                    tl.habilitado=true AND
                    ".$where."
                ORDER BY tl.fecha_creacion DESC;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return $ret;
        }

        $ret = array();

        for ($t = 0; $t < $this->dbTurnos->querySize(); $t++)
        {
            $result = $this->dbTurnos->fetchRow();
            $ret[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function getLlamadosCaducosTurnosEnConsultorios(array $consultorios)
    {
        $where = "";

        if(count($consultorios)>0)
        {
            $where.="(";

            for($i=0; $i < count($consultorios); $i++ )
            {
                $where.=" c.id=".$consultorios[$i]->getId();

                if($i!=count($consultorios)-1){
                    $where.=" OR ";
                }
            }

            $where.=")";
        }

        $query="SELECT
                    tl.id as id,
                    concat(p.nombre,' ',p.apellido) as paciente,
                    e.detalle as especialidad,
                    s.detalle as subespecialidad,
                    concat(prof.nombre,' ',prof.apellido) as profesional,
                    time(tl.fecha_creacion) as hora_llamado,
                    t.idestado_turno as estado_turno
                FROM
                    turnero_llamado tl LEFT JOIN
                    turno t ON(tl.idturno = t.id) LEFT JOIN
                    consultorio c ON(t.idconsultorio=c.id) LEFT JOIN
                    paciente p ON(t.tipodoc = p.tipodoc AND t.nrodoc = p.nrodoc) LEFT JOIN
                    subespecialidad s ON(c.idsubespecialidad=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id) LEFT JOIN
                    profesional prof ON(c.idprofesional=prof.id)
                WHERE
                    tl.habilitado=false AND
                    ".$where."
                ORDER BY tl.fecha_creacion DESC LIMIT 7;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            return $ret;
        }
        
        $ret = array();

        for ($t = 0; $t < $this->dbTurnos->querySize(); $t++)
        {
            $result = $this->dbTurnos->fetchRow();
            $ret[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $ret;
    }

    function darDeBajaTurnosCaducados($cantMin)
    {
        $query="UPDATE
                    turnero_llamado 
                SET 
                    habilitado = FALSE
                WHERE
                    fecha_creacion < DATE_SUB(NOW(), INTERVAL $cantMin MINUTE);";

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

    function hayCambiosEnLlamador($idturnero, $turno_previo, $cantidadLista)
    {
        $std = new stdClass();

        $turnero = $this->getTurnero($idturnero);

        $listado = $this->getLlamadosTurnosEnConsultorios($turnero->getConsultorios());

        $listadoCaducos = $this->getLlamadosCaducosTurnosEnConsultorios($turnero->getConsultorios());

        $atendidos = 0;

        for ($i=0; $i < count($listadoCaducos); $i++) { 
            if($listadoCaducos[$i]['estado_turno']=='3'){
                $atendidos++;
            }
        }

        $std->cantidadAtendidos = $atendidos;

        if(count($listado)==0 AND $turno_previo!=0){//Caso pasaron 5 minutos de espera y pasa a turnos caducos
            $std->response = true;
            $std->llamado = 0;
        } else if(count($listado)>0){//Casos de verificacion de turnos en cabecera con lista llena

            if($listado[0]['id']!=$turno_previo){//El primer turno cambio
                $std->response = true;
                $std->llamado = $listado[0]['id'];
            } else if(count($listado)!=$cantidadLista){//Caso varia el largo de los turnos
                $std->response = true;
                $std->llamado = $listado[0]['id'];
            } else {
                $std->response = false;
                $std->llamado = $turno_previo;
            }
        } else {
            $std->response = false;
            $std->llamado = $turno_previo;
        }

        $std->cantidad = count($listado);

        return $std;
    }

}