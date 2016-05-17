<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once negocio.'profesional.class.php';


class ProfesionalDatabaseLinker
{
    var $dbTurnos;

    function ProfesionalDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function crearProfesional($arrayProfesional)
    {
        $response = new stdClass();
        $query="INSERT INTO 
                    profesional
                        (`nombre`,
                        `apellido`,
                        `matricula_nacional`,
                        `matricula_provincial`,
                        `telefono`,
                        `email`,
                        `idusuario`,
                        `habilitado`)
                VALUES
                    (
                        '".$arrayProfesional['NombreProf']."',
                        '".$arrayProfesional['ApeProf']."',
                        '".$arrayProfesional['MatNac']."',
                        '".$arrayProfesional['MatProv']."',
                        '".$arrayProfesional['TelProf']."',
                        '".$arrayProfesional['MailProf']."',
                        ".$arrayProfesional['slctusuario'].",
                        1
                    );";



        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);    
            $response->message = "Profesional Agregado";
            $response->ret = true;
        }
        catch (Exception $e)
        {
            $response->message = "Ocurrio un error al crear el profesional";
            $response->ret = false;
        }

        return $response;
    }

    function borrarProfesional($id)
    {
        $query="UPDATE
                    profesional
                SET
                    `habilitado`='0'
                WHERE
                    `id`=$id;";
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

    private function getProfesionales($page, $rows, $filters)
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
                    p.id,
                    p.nombre,
                    p.apellido,
                    p.matricula_nacional,
                    p.matricula_provincial,
                    p.email,
                    p.telefono,
                    u.detalle as idusuario,
                    p.habilitado
                FROM
                    profesional p LEFT JOIN
                    usuario u ON(u.idusuario = p.idusuario)

                WHERE
                    p.habilitado = true ".$where."
                LIMIT $rows OFFSET $offset;";


        $this->dbTurnos->ejecutarQuery($query);

        $ret = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $profesional = new Profesional();
            $profesional->setId($result['id']);
            $profesional->setNombre($result['nombre']);
            $profesional->setApellido($result['apellido']);
            $profesional->setMatriculaN($result['matricula_nacional']);
            $profesional->setMatriculaP($result['matricula_provincial']);
            $profesional->setEmail($result['email']);
            $profesional->setTelefono($result['telefono']);
            $profesional->setIdusuario($result['idusuario']);
            $profesional->setHabilitado($result['habilitado']);
            $ret[] = $profesional;
        }

        return $ret;
    }

   private function getCantidadProfesionales($servicio, $filters = null)
    {

        $where = "WHERE habilitado >= '0' ";

        $query="SELECT 
                    COUNT(*) as cantidad
                FROM 
                    profesional ";
        $query .= " " . $where;
        
        $this->dbTurnos->ejecutarQuery($query);
        $result = $this->dbTurnos->fetchRow($query);
        $ret = $result['cantidad'];
        return $ret;
    }


     function getProfesionalesJson($page, $rows, $filters)
    {
        $response = new stdClass();
        $this->dbTurnos->conectar();

        $profarray = $this->getProfesionales($page, $rows, $filters);

        $response->page = $page;
        $response->total = ceil($this->getCantidadProfesionales($filters) / $rows);
        $response->records = $this->getCantidadProfesionales($filters);

        $this->dbTurnos->desconectar();

        for ($i=0; $i < count($profarray) ; $i++) 
        {
            $profesionales = $profarray[$i];
            //id de fila
            $response->rows[$i]['id'] = $profesionales->id; 
            $row = array();
            $row[] = $profesionales->id;
            $row[] = $profesionales->nombre;
            $row[] = $profesionales->apellido;
            $row[] = $profesionales->matricula_n;
            $row[] = $profesionales->matricula_p;
            $row[] = $profesionales->email;
            $row[] = $profesionales->telefono;
            $row[] = $profesionales->idusuario;
            $row[] = '';
            //agrego datos a la fila con clave cell
            $response->rows[$i]['cell'] = $row;
        }

        $response->userdata['Id']= 'Id';
        $response->userdata['idprofesional']= 'idprofesional';
        $response->userdata['nombre']= 'nombre';
        $response->userdata['apellido']= 'apellido';
        $response->userdata['matricula_n']= 'matricula_n';
        $response->userdata['matricula_p']= 'matricula_p';
        $response->userdata['email']= 'email';
        $response->userdata['telefono']= 'telefono';
        $response->userdata['idusuario']= 'idusuario';
        $response->userdata['myac'] = '';

        return json_encode($response);
    }

    function modificarProfesional($data)
    {

        $response = new stdClass();

        $query= "UPDATE profesional SET nombre = '".$data['nombre']."' ,apellido = '".$data['apellido']."'
        WHERE id = ".$data['idprofesional'].";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
            $this->dbTurnos->desconectar();
            $response->message = "Profesional modificado";
            $response->ret = true;

        }
        catch (Exception $e)
        {
            $response->message = "Hubo un error modificando el profesional.";
            $response->ret = false;
        }

        return $response;

    }

    function getProfesional($idprofesional)
    {
        $query="SELECT
                    id,
                    nombre,
                    apellido,
                    matricula_nacional,
                    matricula_provincial,
                    email,
                    telefono,
                    idusuario
                FROM
                    profesional
                WHERE 
                    habilitado=1 AND
                    id=$idprofesional;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el profesional con id=".$idprofesional, 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        $profesional = new Profesional();
        $profesional->setId($result['id']);
        $profesional->setNombre($result['nombre']);
        $profesional->setApellido($result['apellido']);
        $profesional->setMatriculaN($result['matricula_nacional']);
        $profesional->setMatriculaP($result['matricula_provincial']);
        $profesional->setEmail($result['email']);
        $profesional->setTelefono($result['telefono']);
        $profesional->setIdusuario($result['idusuario']);

        return $profesional;
    }
}