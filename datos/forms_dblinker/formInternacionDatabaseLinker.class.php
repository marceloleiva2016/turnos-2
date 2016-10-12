<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'generalesDatabaseLinker.class.php';
include_once datos.'pacienteDatabaseLinker.class.php';
include_once datos.'profesionalDatabaseLinker.class.php';
include_once datos.'atencionDatabaseLinker.class.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once neg_formulario.'formInternacion/formInternacion.class.php';
include_once neg_formulario.'formInternacion/formInternacionObservacion.class.php';
include_once neg_formulario.'formInternacion/formInternacionItemObservacion.class.php';
include_once neg_formulario.'formInternacion/formInternacionEgreso.class.php';
include_once neg_formulario.'formInternacion/formInternacionLaboratorio.class.php';

class FormInternacionDatabaseLinker
{
    private $dbTurnos;

//-------------------------------------Constructor--------------------------------------------

    function FormInternacionDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        if (!isset($_SESSION)) 
        {
            session_start();
        }
    }

    function getId($idAtencion)
    {
        $query="SELECT
                    id
                FROM
                    form_internacion
                WHERE
                    idatencion = ".Utils::phpIntToSQL($idAtencion).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("Error consultando el formulario con el turno en FormInternacion!", 1);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['id'];
    }

    function crear($idAtencion, $tipodoc, $nrodoc, $iduser)
    {
        $query="INSERT INTO
                    form_internacion(
                            `tipodoc`,
                            `nrodoc`,
                            `idatencion`,
                            `fecha_creacion`,
                            `idusuario`,
                            `habilitado`)
                VALUES (
                        ".Utils::phpIntToSQL($tipodoc).",
                        ".Utils::phpIntToSQL($nrodoc).",
                        ".Utils::phpIntToSQL($idAtencion).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
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
            throw new Exception("Error consultando el formulario con el turno en FormInternacion!", 1);
        }

        $id = $this->dbTurnos->ultimoIdInsertado();

        $this->dbTurnos->desconectar();

        return $id;
    }

    /**
    * @return FormInternacion
    * devuelve un objeto FormInternacion con todo cargado
    */
    function obtenerFormulario($idAtencion, $iduser)
    {
        $id = $this->getId($idAtencion);

        $dbAtencion = new AtencionDatabaseLinker();
        $dbPaciente = new PacienteDatabaseLinker();
        
        $datosAtencion = $dbAtencion->obtenerVariablesAtencion($idAtencion);
        $paciente = $dbPaciente->getDatosPacientePorNumero($datosAtencion['tipodoc'], $datosAtencion['nrodoc']);

        if(is_null($id))
        {
            $id = $this->crear($idAtencion, $datosAtencion['tipodoc'], $datosAtencion['nrodoc'], $iduser);
        }

        $FormInternacion = new FormInternacion();
        $FormInternacion->setId($id);
        $FormInternacion->setIdAtencion($idAtencion);
        $FormInternacion->setPaciente($paciente);
        $this->cargarObservaciones($FormInternacion);
        $this->cargarEgreso($FormInternacion);
        $this->cargarLaboratorios($FormInternacion);

        return $FormInternacion;
    }

    public function cargarLaboratorios(FormInternacion $form)
    {
        $listaOrina = $this->laboratoriosHechos($form->getId(), TipoLaboratorio::ORINA);
        $listaSangre = $this->laboratoriosHechos($form->getId(), TipoLaboratorio::SANGRE);

        $form->setEstudiosLaboratorioOrina($listaOrina);
        $form->setEstudiosLaboratorioSangre($listaSangre);
    }

    public function traerTiposObservaciones($idform_internacion)
    {
        $query="SELECT
                    dto.id,
                    dto.detalle
                FROM
                    form_internacion_tipo_observacion dto
                WHERE
                    dto.id IN(SELECT
                                DISTINCT(do.idtipo_observacion) AS id
                            FROM
                                form_internacion_observaciones do
                            WHERE
                                do.idform_internacion=".Utils::phpIntToSQL($idform_internacion)." AND
                                do.habilitado=1);";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $observaciones = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $observacion = new FormInternacionObservacion();
            $observacion->setTipo($result['id']);
            $observacion->setDetalle($result['detalle']);
            $observaciones[] = $observacion;
        }

        $this->dbTurnos->desconectar();

        return $observaciones;
    }

    public function cargarObservaciones(FormInternacion $dem)
    {
        $observaciones = $this->traerTiposObservaciones($dem->getId());
    
        for ($i=0; $i < count($observaciones); $i++)
        {
            $query="SELECT
                        do.id AS id,
                        do.detalle AS detalle,
                        do.fecha_ingreso AS fecha,
                        u.nombre AS usuario
                    FROM 
                        form_internacion_observaciones do JOIN
                        usuario u ON(do.iduser=u.idusuario)
                    WHERE
                        do.idform_internacion=".Utils::phpIntToSQL($dem->getId())." AND
                        do.idtipo_observacion=".Utils::phpIntToSQL($observaciones[$i]->getTipo())." AND
                        do.habilitado=1;";



            $this->dbTurnos->conectar();

            try
            {
                $this->dbTurnos->ejecutarQuery($query);
            }
            catch (Exception $e)
            {
                $this->dbTurnos->desconectar();
                throw new Exception("No se pudo traer items de la observacion ".$observaciones[$i]->getDetalle()." para la FormInternacion con id = ".$dem->getId().", Por favor comuniquese con informatica con esa informacion de error. Gracias!", 201230);
            }

            for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
            {
                $result = $this->dbTurnos->fetchRow($query);
                $fecha = Utils::phpTimestampToHTMLDateTime(Utils::sqlDateTimeToPHPTimestamp($result['fecha']));
                $observaciones[$i]->agregarItem($result['id'], $fecha, $result['usuario'], $result['detalle']);
            }

            $this->dbTurnos->desconectar();
        }
    
        $dem->agregarObservaciones($observaciones);

        $this->dbTurnos->desconectar();
    }

    function cargarEgreso(FormInternacion $dem)
    {
        $variablesEgreso = $this->getEgreso($dem->getId());

        $egreso = new Egreso();
        $egreso->setId($variablesEgreso['id']);
        $egreso->setDiagnostico($variablesEgreso['diagnostico']);
        $egreso->setTipoEgreso($variablesEgreso['tipo_egreso']);
        $egreso->setFechaCreacion($variablesEgreso['fecha_creacion']);
        $egreso->setUsuario($variablesEgreso['usuario']);

        $dem->setEgreso($egreso);
    }

    public function editarObservacion($idObservacion,$nuevoTexto)
    {
        $response = new stdClass();

        $response->result = true;

        $obsAnt = $this->obtenerObservacion($idObservacion);

        try
        {
            $this->eliminarObservacion($idObservacion);
        }
        catch(Exception $e)
        {
            $response->message = "Ocurrio un error al actualizar el detalle de la observacion";
            $response->result = false;
        }

        $query="INSERT INTO form_internacion_observaciones
                    (
                    `idform_internacion`,
                    `idtipo_observacion`,
                    `detalle`,
                    `fecha_ingreso`,
                    `iduser`,
                    `habilitado`
                    )
                    VALUES
                    (
                    ".Utils::phpIntToSQL($obsAnt['idform_internacion']).",
                    ".Utils::phpIntToSQL($obsAnt['idtipo_observacion']).",
                    ".Utils::phpStringToSQL($nuevoTexto).",
                    now(),
                    ".Utils::phpIntToSQL($obsAnt['iduser']).",
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
            $response->message = "Ocurrio un error al insertar la observacion nueva";
            $response->result = false;
        }

        $this->dbTurnos->desconectar();

        return $response;
    }

    public function obtenerObservacion($idObservacion)
    {
        $query="SELECT
                    id,
                    idform_internacion,
                    idtipo_observacion,
                    detalle,
                    fecha_ingreso,
                    iduser,
                    habilitado
                FROM
                    form_internacion_observaciones
                WHERE
                    id=".$idObservacion.";";
        try 
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch(Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer la cantidad de antecedentes de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow();

        $this->dbTurnos->desconectar();

        $obs = array();

        $obs['id'] = $result['id'];
        $obs['idform_internacion'] = $result['idform_internacion'];
        $obs['idtipo_observacion'] = $result['idtipo_observacion'];
        $obs['detalle'] = $result['detalle'];
        $obs['fecha_ingreso'] = $result['fecha_ingreso'];
        $obs['iduser'] = $result['iduser'];
        $obs['habilitado'] = $result['habilitado'];

        return $obs;
    }

    public function eliminarObservacion($idObservacion)
    {
        $query="UPDATE 
                    form_internacion_observaciones
                SET
                    habilitado='0'
                WHERE
                    id=".$idObservacion.";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo eliminar la observacion", 201230);
        }
        $this->dbTurnos->desconectar();

        return true;
    }

    public function nombreTipoObservacion($idTipoObs)
    {
        $query="SELECT
                    detalle
                FROM
                    form_internacion_tipo_observacion
                WHERE 
                    id=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones selecionadas para la epìcrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        return $result['detalle'];
    }

    public function obtenerCantidadObservacionesDeTipo($idform_internacion, $idTipoObs)
    {
        $query="SELECT 
                    count(*) AS cantidad
                FROM 
                    form_internacion_observaciones do JOIN
                    usuario u ON(do.iduser=u.idusuario)
                WHERE
                    idform_internacion=".Utils::phpIntToSQL($idform_internacion)." AND
                    idtipo_observacion=".Utils::phpIntToSQL($idTipoObs).";";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return Utils::sqlIntToPHP($result['cantidad']);
    }

    public function insertarObservacion($idform_internacion, $tipoObs, $texto, $iduser)
    {
        $query="INSERT INTO 
                    form_internacion_observaciones
                    (
                        `idform_internacion`,
                        `idtipo_observacion`,
                        `detalle`,
                        `fecha_ingreso`,
                        `iduser`,
                        `habilitado`
                    )
                VALUES
                    (
                        ".Utils::phpIntToSQL($idform_internacion).",
                        ".Utils::phpIntToSQL($tipoObs).",
                        ".Utils::phpStringToSQL($texto).",
                        now(),
                        ".$iduser.",
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
            throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
        }

        $this->dbTurnos->desconectar();
        return true;
    }


    function getTiposEgresos()
    {
        $query="SELECT
                    id,
                    detalle
                FROM
                    form_internacion_tipo_egreso
                WHERE
                    habilitado=1;";
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $destinos  = array();

        for ($i = 0; $i < $this->dbTurnos->querySize; $i++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $destinos[] = $result;
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $destinos;
    }

    function insertarEgreso($idform_internacion, $idTipoEgreso, $idDiagnostico, $iduser)
    {
        $query="INSERT INTO
                    form_internacion_egreso
                        (`idform_internacion`,
                        `idtipo_egreso_internacion`,
                        `iddiagnostico`,
                        `fecha_creacion`,
                        `iduser`,
                        `habilitado`)
                VALUES (
                        ".Utils::phpIntToSQL($idform_internacion).",
                        ".Utils::phpIntToSQL($idTipoEgreso).",
                        ".Utils::phpIntToSQL($idDiagnostico).",
                        now(),
                        ".Utils::phpIntToSQL($iduser).",
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
            throw new Exception("Fallo un proceso de insersion de los datos en intervenciones, mas espeficicamente la insercion: ".$query, 201230);
        }

        $this->dbTurnos->desconectar();

        return true;
    }

    function getEgreso($idform_internacion)
    {
        $query="SELECT
                    de.id as id,
                    dte.detalle as tipo_egreso,
                    d.descripcion as diagnostico,
                    de.fecha_creacion as fecha_creacion,
                    u.nombre as usuario
                FROM
                    form_internacion_egreso de LEFT JOIN 
                    usuario u ON(u.idusuario=de.iduser) LEFT JOIN
                    diagnosticos_diagnostico d ON(de.iddiagnostico=d.id) LEFT JOIN
                    form_internacion_tipo_egreso dte ON(dte.id= de.idtipo_egreso_internacion)
                WHERE
                    de.habilitado=true AND
                    de.idform_internacion = ".Utils::phpIntToSQL($idform_internacion).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;
    }

    function puedeDarEgreso($idform_internacion)
    {   
        $std = new StdClass();
        $std->puede = true;

        $cantidad = $this->cantidadObservaciones($idform_internacion);

        if($cantidad==0)
        {
            $std->puede = false;
            $std->message = "No se cargo ninguna observacion";
        }

        $egreso=$this->getEgreso($idform_internacion);

        if($egreso['id']!=null)
        {
            $std->puede = false;
            $std->message = "Ya existe un egreso cargado";
        }

        return $std;
    }

    function cantidadObservaciones($idform_internacion)
    {
        $query="SELECT
                    count(*) as cantidad
                FROM
                    form_internacion_observaciones
                WHERE 
                    idform_internacion=".Utils::phpIntToSQL($idform_internacion).";";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['cantidad'];
    }

    function getIdAtencion($idform_internacion)
    {
        $query="SELECT
                    idatencion
                FROM
                    form_internacion
                WHERE
                    id=$idform_internacion";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer las observaciones de la epicrisis", 201230);
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result['idatencion'];
    }

    function laboratoriosHechos($idFormInt, $tipoLab)
    {
        $sql = "SELECT 
                    lab.form_internacion_lista_laboratorio_id as id,
                    lab.value as valor,
                    lab.text_value as text_value,
                    listLab.es_numerico as es_numerico,
                    listLab.nombre as nombre,
                    listLab.descripcion as descripcion,
                    listLab.tipo_laboratorio  as tipo
                FROM 
                    form_internacion_laboratorios lab left join
                    form_internacion_lista_laboratorio listLab ON (lab.form_internacion_lista_laboratorio_id = listLab.id)
                WHERE 
                    lab.form_internacion_id = ".Utils::phpIntToSQL($idFormInt)." AND
                    listLab.tipo_laboratorio = ". Utils::phpIntToSQL($tipoLab).";";
        
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            throw new Exception("No se pudo traer la informacion del ticket", 201230);
        }
        $ret = array();
        
        for ($i = 0; $i < $this->dbTurnos->querySize(); $i++) {
            $result = $this->dbTurnos->fetchRow();
            $laboratorio = new Labortatorio();
            $laboratorio->id = Utils::sqlIntToPHP($result['id']);
            $laboratorio->nombre = htmlentities($result['nombre']);
            $laboratorio->descripcion = htmlentities($result['descripcion']);
            $laboratorio->esNumerico = Utils::sqlBoolToPHP($result['es_numerico']);
            if($laboratorio->esNumerico )
            {
                $laboratorio->valor = Utils::sqlFloatToPHP($result['valor']);
            }
            else 
            {
                $laboratorio->valor = $result['text_value'];
            }
            
            $ret[$i]=$laboratorio;
        }
        
        return $ret;
    }
    
    function esLaboratorioNumerico($idLab)
    {
        $sql = "SELECT 
                    lab.es_numerico as es_numerico
                FROM 
                    form_internacion_lista_laboratorio lab
                WHERE 
                    lab.id = ".Utils::phpIntToSQL($idLab). "
                    ;";
        
        //$this->firephp->log($sql);
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            throw new Exception("No se pudo ejecutar la consulta: " . $sql, 201230);
        }
        
        $ret = true;
        if($this->dbTurnos->querySize()>0)
        {
            $result = $this->dbTurnos->fetchRow();
            $ret = Utils::sqlBoolToPHP($result['es_numerico']);
        }
        
        return $ret;
    }
    
    function laboratoriosSinHacer($idFormInt, $tipoLab)
    {
        $sql = "SELECT 
                    lab.id as id,
                    lab.nombre as nombre,
                    lab.descripcion as descripcion,
                    lab.es_numerico as es_numerico
                FROM 
                    form_internacion_lista_laboratorio lab
                WHERE 
                    not 
                        (lab.id in (SELECT 
                                    form_internacion_lista_laboratorio_id 
                                FROM 
                                    form_internacion_laboratorios 
                                WHERE 
                                form_internacion_id = ".Utils::phpIntToSQL($idFormInt)."
                                ))
                    and lab.favorito > 0
                    and lab.tipo_laboratorio = ". Utils::phpIntToSQL($tipoLab)."
                    Order By favorito asc;";
        
        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            throw new Exception("No se pudo ejecutar la consulta: " . $sql, 201230);
        }
        $ret = array();
        
        for ($i = 0; $i < $this->dbTurnos->querySize(); $i++) {
            $result = $this->dbTurnos->fetchRow();
            $laboratorio = new Labortatorio();
            $laboratorio->id = Utils::sqlIntToPHP($result['id']);
            $laboratorio->nombre = htmlentities($result['nombre']);
            $laboratorio->descripcion = htmlentities($result['descripcion']);
            $laboratorio->esNumerico = Utils::sqlBoolToPHP($result['es_numerico']);
            $ret[$i]=$laboratorio;
        }
        
        return $ret;
        
    }
    
    function hayLaboratoriosSinHacer($idFormInt)
    {
        $sql = "SELECT 
                    count(*) as cantidad
                FROM 
                    form_internacion_lista_laboratorio 
                WHERE 
                    not 
                        (id in (select 
                                form_internacion_lista_laboratorio_id 
                                from 
                                form_internacion_laboratorios 
                                where 
                                form_internacion_id = ".Utils::phpIntToSQL($idFormInt)."
                                ))
                    and favorito > 0
                    Order By favorito asc;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($sql);
        }
        catch (Exception $e)
        {
            throw new Exception("No se pudo traer la informacion del ticket", 201230);
        }
        
        
        
        $result = $this->dbTurnos->fetchRow();
        $cantidad= Utils::sqlIntToPHP($result['cantidad']);
        return $cantidad>0;
        
    }

    function insertarLaboratorio($idFormInt, Labortatorio $laboratorio, $idusuario)
    {
        $query ="insert into 
                    form_internacion_laboratorios 
                    (
                    form_internacion_lista_laboratorio_id,
                    form_internacion_id,";
        
        if($laboratorio->esNumerico)
        {
            $query .= " value ,";
        }
        else 
        {
            $query .= " text_value ,";
        }
            
        $query .="  idusuario,
                    fecha_creacion
                    )
                    values
                    (
                    ".Utils::phpIntToSQL($laboratorio->id).",
                    ".Utils::phpIntToSQL($idFormInt).", ";
        if($laboratorio->esNumerico)
        {
            $query .= Utils::phpFloatToSQL($laboratorio->valor) . " ,";
        }
        else 
        {
            $query .= Utils::phpStringToSQL($laboratorio->valor) . " ,";
        }
                    
        $query .=   $idusuario.",
                    now()
                    );
            ";
        
        try {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarAccion($query);
        } catch (Exception $e) {
            throw new Exception("Error intentando ejecutar: $query");
        }
        
        $this->dbTurnos->desconectar();
    }

}