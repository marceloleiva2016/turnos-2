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

    function modificarPaciente($arrayPaciente)
    {
        $query="UPDATE
                    paciente
                SET 
                    nombre='".$arrayPaciente['nombre']."',
                    apellido='".$arrayPaciente['apellido']."',
                    sexo='".$arrayPaciente['sexo']."',
                    fecha_nacimiento=".$arrayPaciente['fecha_nac'].",
                    idpais=".Utils::phpIntToSQL($arrayPaciente['pais']).",
                    idprovincia=".Utils::phpIntToSQL($arrayPaciente['provincia']).",
                    idpartido=".Utils::phpIntToSQL($arrayPaciente['partido']).",
                    idlocalidad=".Utils::phpIntToSQL($arrayPaciente['localidad']).",
                    codigo_postal='".$arrayPaciente['cp']."',
                    calle_nombre='".$arrayPaciente['calle_nombre']."',
                    calle_numero='".$arrayPaciente['calle_numero']."',
                    piso='".$arrayPaciente['piso']."',
                    departamento='".$arrayPaciente['departamento']."',
                    telefono='".$arrayPaciente['telefono']."',
                    telefono2='".$arrayPaciente['telefono2']."',
                    es_donante='".$arrayPaciente['donante']."',
                    email='".$arrayPaciente['email']."',
                    fecha_modificacion=now(),
                    idusuario='".$arrayPaciente['usuario']."'
                WHERE
                    tipodoc='".$arrayPaciente['tipodoc']."' AND
                    nrodoc='".$arrayPaciente['nrodoc']."';";
                    
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

    function getDatosPacientePorNombre($filters)
    {
        $filtersEnArray=Utils::stringToArray($filters);

        $where = "";

        for ($i=0; $i < count($filtersEnArray); $i++) {

            $where.="CONCAT(nombre,' ',apellido) LIKE '%".$filtersEnArray[$i]."%'";
            if(count($filtersEnArray)-1!=$i){
                $where.=" AND ";
            }
        }

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
                    ".$where.";";

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

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
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
            $paciente->setFechaModificacion($result['fecha_modificacion']);
            $paciente->setFechaCreacion($result['fecha_creacion']);
            $ret[] = $paciente;
        }

        return $ret;
    }

    function existePaciente($tipodoc, $nrodoc)
    {
        $query="SELECT 
                   *
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

        return $result!=false;
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
        $paciente->setFechaModificacion($result['fecha_modificacion']);
        $paciente->setFechaCreacion($result['fecha_creacion']);

        $this->dbTurnos->desconectar();

        return $paciente;
    }
}