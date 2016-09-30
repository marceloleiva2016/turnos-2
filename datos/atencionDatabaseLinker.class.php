<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'internacionDatabaseLinker.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'formularioDatabaseLinker.class.php';

class AtencionDatabaseLinker
{
    var $dbTurnos;

    function AtencionDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function obtenerDatosParaAtencion($id, $idTipoAtencion)
    {
        //Obtengo todo lo del turno
        $dbturno = new TurnoDatabaseLinker();
        $dbinternacion = new InternacionDatabaseLinker();

        switch ($idTipoAtencion) {
            case 1://Es un turno por demanda
                return $dbturno->obtenerVariablesTurno($id);
                break;
            case 2://Es un turno por consultorio programado
                return $dbturno->obtenerVariablesTurno($id);
                break;
            case 3://Es una internacion
                return $dbinternacion->obtenerVariablesInternacion($id);
                break;
        }
    }

    function crear($id, $idTipoAtencion, $iduser)
    {
        $variables_necesarias = $this->obtenerDatosParaAtencion($id, $idTipoAtencion);
        
        $query="INSERT INTO
                    atencion(
                        `tipodoc`,
                        `nrodoc`,
                        `idprofesional`,
                        `idsubespecialidad`,
                        `idturno`,
                        `idtipo_atencion`,
                        `fecha_creacion`,
                        `idusuario`,
                        `habilitado`)
                VALUES (
                        ".Utils::phpIntToSQL($variables_necesarias['tipodoc']).",
                        ".Utils::phpIntToSQL($variables_necesarias['nrodoc']).",
                        ".Utils::phpIntToSQL($variables_necesarias['idprofesional']).",
                        ".Utils::phpIntToSQL($variables_necesarias['idsubespecialidad']).",
                        ".Utils::phpIntToSQL($id).",
                        ".Utils::phpIntToSQL($idTipoAtencion).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
                        1
                        );";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $idAtencion = $this->dbTurnos->ultimoIdInsertado();

        $this->dbTurnos->desconectar();

        return $idAtencion;
    }

    function existeAtencion($idTurno, $idTipoAtencion)
    {
        $query="SELECT
                    id
                FROM
                    atencion
                WHERE 
                    idturno = $idTurno AND
                    idtipo_atencion = $idTipoAtencion;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result!=null;
    }

    function obtenerVariablesAtencion($idAtencion)
    {
        $query="SELECT
                    tipodoc,
                    nrodoc,
                    idprofesional,
                    idsubespecialidad,
                    idturno,
                    idtipo_atencion,
                    fecha_creacion
                FROM
                    atencion
                WHERE
                    id=$idAtencion;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();
        return $result;
    }

    function obtenerId($idTurno, $idTipoAtencion)
    {
        $query="SELECT
                    id
                FROM
                    atencion
                WHERE
                    idturno = $idTurno AND
                    idtipo_atencion = $idTipoAtencion;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function obtenerIdTurno($idAtencion)
    {
        $query="SELECT
                    idturno as id
                FROM
                    atencion
                WHERE
                    id=$idAtencion;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function obtenerFormularioPrincipalDeAtencion($idAtencion)
    {
        $query="SELECT
                    af.idformulario as idFormulario
                FROM
                    atencion_formulario af LEFT JOIN
                    formulario f ON(af.idformulario=f.id)
                WHERE
                    f.nivel = 'PRINCIPAL' and
                    af.habilitado=1 and
                    af.idatencion = $idAtencion;";
                    
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con la atencion!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idFormulario'];
    }

    function crearFormularioEnAtencion($idAtencion, $idFormulario, $iduser)
    {
        $query="INSERT INTO 
                    atencion_formulario(
                        `idatencion`,
                        `idformulario`,
                        `fecha_creacion`,
                        `iduser`,
                        `habilitado`)
                VALUES(
                    '".$idAtencion."',
                    '".$idFormulario."',
                    now(),
                    '".$iduser."',
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
            throw new Exception("Error consultando el formulario con el turno en demanda!", 1);
        }

        $this->dbTurnos->desconectar();
    }

    function getSubespecialidad($idAtencion)
    {
        $query="SELECT
                    idsubespecialidad
                FROM
                    atencion
                WHERE
                    id = $idAtencion";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el idsubespecialidad en la atencion!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idsubespecialidad'];
    }

    function getTipoAtencion($idAtencion)
    {
        $query="SELECT
                    idtipo_atencion
                FROM
                    atencion
                WHERE
                    id = $idAtencion";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el idtipo_atencion en la atencion!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idtipo_atencion'];
    }

    function getIdTurnoEnAtencion($idAtencion)
    {
        $query="SELECT
                    idturno
                FROM
                    atencion
                WHERE
                    id=$idAtencion;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el idturno en la atencion!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idturno'];
    }

    function getAtencionesEnPaciente($tipodoc, $nrodoc, $especialidadFiltro)
    {
        $filtro = "";
        if($especialidadFiltro!="" OR $especialidadFiltro!=null){
            $filtro .= " AND e.id=".$especialidadFiltro;
        }

        $query="SELECT
                    a.id as idatencion,
                    a.idturno,
                    e.id as idespecialidad,
                    e.detalle as especialidad,
                    s.id as idsubespecialidad,
                    s.detalle as subespecialidad,
                    CONCAT(p.nombre,' ',p.apellido) as profesional,
                    ta.detalle as tipo_atencion,
                    a.idusuario,
                    DATE(a.fecha_creacion) as fecha,
                    TIME(a.fecha_creacion) as hora,
                    a.fecha_creacion,
                    f.ubicacion,
                    f.icono
                FROM
                    atencion a LEFT JOIN
                    tipo_atencion ta ON(a.idtipo_atencion=ta.id) LEFT JOIN
                    atencion_formulario af ON(af.idatencion=a.id AND af.habilitado=true) LEFT JOIN
                    formulario f ON(af.idformulario=f.id) LEFT JOIN
                    profesional p ON(a.idprofesional=p.id) LEFT JOIN
                    subespecialidad s ON(a.idsubespecialidad=s.id) LEFT JOIN
                    especialidad e ON(s.idespecialidad=e.id)
                WHERE
                    a.tipodoc=$tipodoc AND
                    a.nrodoc=$nrodoc AND
                    a.habilitado=true ".$filtro."
                ORDER BY
                    a.fecha_creacion DESC;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo consultar las atenciones del paciente", 201230);
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