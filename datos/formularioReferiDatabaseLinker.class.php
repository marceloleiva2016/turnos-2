<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once dat_formulario.'demandaDatabaseLinker.class.php';
include_once 'subespecialidadDatabaseLinker.class.php';
include_once 'formularioDatabaseLinker.class.php';

class formularioReferiDatabaseLinker
{
    var $dbTurnos;
    var $dbform;

    function formularioReferiDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
        $this->dbform = new FormularioDatabaseLinker();
        if (!isset($_SESSION)) 
        {
            session_start();
        }
    }

    function delegarFormulario($idAtencion, $iduser)
    {
        //selecciono el tipo de formulario de la atencion
        $dbAtencion = new AtencionDatabaseLinker();

        $existe = $dbAtencion->existeAtencion($idAtencion);

        if($existe)
        {
            //ya esta creada sino creo el id
            $idAtencion = $dbAtencion->obtenerId($idAtencion);
        }
        else
        {
            //creo la atencion
            $idAtencion = $dbAtencion->crear($idAtencion, $iduser);
        }

        $idSubespecialidad = $dbAtencion->getSubespecialidad($idAtencion);

        $idTipoAtencion = $dbAtencion->getTipoAtencion($idAtencion);

        //obtengo el formulario
        $idFormulario = $dbAtencion->obtenerFormularioPrincipalDeAtencion($idAtencion);

        if($idFormulario==null)
        {
            //lo vuelvo a setear
            $idFormulario = $this->dbform->obtenerIdFormulario($idSubespecialidad, $idTipoAtencion);
            if($idFormulario!=null)
            {
                //creo formulario default
                $dbAtencion->crearFormularioEnAtencion($idAtencion, $idFormulario, $iduser);
            }
            else
            {
                throw new Exception("Error intentando crear el formulario nuevo!", 1);
            }
        }

        //redirecciono al formulario
        $this->redireccionar($idAtencion, $idFormulario);
    }

    function redireccionar($idAtencion, $idFormulario)
    {
        $formulario = $this->dbform->getFormulario($idFormulario);

        if($formulario!=false)
        {
            header('Location: ../formulario'.$formulario['ubicacion'].'?id='.$idAtencion);
        }
    }
}
