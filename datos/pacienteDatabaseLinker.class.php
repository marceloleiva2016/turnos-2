<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'paciente.class.php';
include_once 'generalesDatabaseLinker.class.php';
include_once 'utils.php';

class PacienteDatabaseLinker
{
    var $dbTurnos;

    function PacienteDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function crearPaciente($arrayPaciente)
    {
        $query="INSERT INTO 
                    paciente(
                        `tipodoc`,
                        `nrodoc`,
                        `nombre`,
                        `apellido`,
                        `sexo`,
                        `fecha_nacimiento`,
                        `edad_ingreso`,
                        `idpais`,
                        `idprovincia`,
                        `idpartido`,
                        `idlocalidad`,
                        `codigo_postal`,
                        `calle_nombre`,
                        `calle_numero`,
                        `piso`,
                        `departamento`,
                        `telefono`,
                        `telefono2`,
                        `es_donante`,
                        `email`,
                        `fecha_modificacion`,
                        `fecha_creacion`,
                        `idusuario`)
                VALUES
                    (
                        ".Utils::phpIntToSQL($arrayPaciente['tipodoc']).",
                        ".Utils::phpIntToSQL($arrayPaciente['nrodoc']).",
                        '".$arrayPaciente['nombre']."',
                        '".$arrayPaciente['apellido']."',
                        '".$arrayPaciente['sexo']."',
                        ".$arrayPaciente['fecha_nac'].",
                        '".$arrayPaciente['edad']."',
                        ".Utils::phpIntToSQL($arrayPaciente['pais']).",
                        ".Utils::phpIntToSQL($arrayPaciente['provincia']).",
                        ".Utils::phpIntToSQL($arrayPaciente['partido']).",
                        ".Utils::phpIntToSQL($arrayPaciente['localidad']).",
                        '".$arrayPaciente['cp']."',
                        '".$arrayPaciente['calle_nombre']."',
                        '".$arrayPaciente['calle_numero']."',
                        '".$arrayPaciente['piso']."',
                        '".$arrayPaciente['departamento']."',
                        '".$arrayPaciente['telefono']."',
                        '".$arrayPaciente['telefono2']."',
                        '".$arrayPaciente['donante']."',
                        '".$arrayPaciente['email']."',
                        now(),
                        now(),
                        '".$arrayPaciente['usuario']."'
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

        return true;
    }

    private function getPacientes($page, $rows, $filters)
    {
        $where = "";
        if(count($filters)>0)
        {
            for($i=0; $i < count($filters['rules']); $i++ )
            {
                $where.=$filters['groupOp']." ";
                $where.="prod.".$filters['rules'][$i]['field']." like '".$filters['rules'][$i]['data']."%'";
            }
        }

        $offset = ($page - 1) * $rows;

        $query="SELECT
                    id,
                    nombre,
                    apellido,
                    tipodoc,
                    nrodoc,
                    edad,
                    idosoc
                FROM
                    paciente
                WHERE
                    ".$where."
                LIMIT $rows OFFSET $offset;";

        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
                $result = $this->dbTurnos->fetchRow($query);
                $paciente = new Paciente();
                $paciente->setId($result['id']);
                $paciente->setTipodoc($result['tipodoc']);
                $paciente->setNrodoc($result['nrodoc']);
                $paciente->setNombre($result['nombre']);
                $paciente->setApellido($result['apellido']);
                $paciente->setEdad($result['edad']);
                $paciente->setOsoc($result['idosoc']);
                $ret[] = $paciente;
        }

        return $ret;
    }

    function getDatosPacientePorNumero($tipodoc, $nrodoc)
    {
        $query="SELECT 
                    tipodoc,
                    nrodoc,
                    nombre,
                    apellido,
                    sexo,
                    fecha_nacimiento,
                    edad_ingreso,
                    idpais,
                    idprovincia,
                    idpartido,
                    idlocalidad,
                    codigo_postal,
                    calle_nombre,
                    calle_numero,
                    piso,
                    departamento,
                    telefono,
                    telefono2,
                    es_donante,
                    email,
                    fecha_modificacion,
                    fecha_creacion,
                    idusuario
                FROM
                    paciente
                WHERE
                    tipodoc=$tipodoc AND
                    nrodoc=$nrodoc;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el paciente", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $paciente = new Paciente();
        $paciente->setTipodoc($result['tipodoc']);
        $paciente->setNrodoc($result['nrodoc']);
        $paciente->setNombre($result['nombre']);
        $paciente->setApellido($result['apellido']);
        $paciente->setSexo($result['sexo']);
        $paciente->setFechaNacimiento($result['fecha_nacimiento']);
        $paciente->setEdadActual(Utils::calcularEdad($result['fecha_nacimiento']));
        $paciente->setEdadIngreso($result['edad_ingreso']);
        $paciente->setPais($result['idpais']);
        $paciente->setProvincia($result['idprovincia']);
        $paciente->setPartido($result['idpartido']);
        $paciente->setLocalidad($result['idlocalidad']);
        $paciente->setCP($result['codigo_postal']);
        $paciente->setCalleNombre($result['calle_nombre']);
        $paciente->setCalleNumero($result['calle_numero']);
        $paciente->setPiso($result['piso']);
        $paciente->setDepartamento($result['departamento']);
        $paciente->setTelefono($result['telefono']);
        $paciente->setTelefono2($result['telefono2']);
        $paciente->setEsDonante($result['es_donante']);
        $paciente->setEmail($result['email']);

        $this->dbTurnos->desconectar();

        return $paciente;
    }
}