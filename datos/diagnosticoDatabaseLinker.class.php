<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';

class DiagnosticoDatabaseLinker
{
    var $dbTurnos;

    function DiagnosticoDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    function getCapitulos()
    {
        $query="SELECT
                    id,
                    descripcion
                FROM
                    diagnosticos_capitulo;";

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);    
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los capitulos del diagnostico", 201230);            
        }

        $capitulos = array();

        for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $capitulos[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $capitulos;
    }


    function getGrupo($idCapitulo=NULL)
    {
        $where = ";";

        if($idCapitulo!=NULL)
        {
            $where = "WHERE capitulo_id=$idCapitulo;";    
        }

        $query="SELECT
                    id,
                    descripcion
                FROM
                    diagnosticos_capitulo
                ".$where;

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);    
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los capitulos del diagnostico", 201230);            
        }

        $grupos = array();

        for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $grupos[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $grupos;
    }

    function getCategoria($idGrupo=NULL)
    {
        $where = ";";

        if($idCapitulo!=NULL)
        {
            $where = "WHERE grupo_id=$idGrupo;";    
        }

        $query="SELECT 
                    id,
                    descripcion
                FROM
                    diagnosticos_categoria;
                ".$where;

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);    
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los grupos del diagnostico", 201230);            
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;   
    }

    function getDiagnostico($idCategoria=NULL)
    {
        $where = ";";

        if($idCapitulo!=NULL)
        {
            $where = "WHERE categoria_id=$idCategoria;";    
        }

        $query="SELECT
                    id,
                    codigo_completo,
                    descripcion,
                    sexo
                FROM
                    diagnosticos_diagnostico
                ".$where;

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);    
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los grupos del diagnostico", 201230);            
        }

        $result = $this->dbTurnos->fetchRow($query);

        $this->dbTurnos->desconectar();

        return $result;
    }

    function getDiagFiltrado($codigo, $nombre)
    {
        $where = "WHERE ";
        if($codigo!=null AND $nombre!=null)
        {
            $where .= "codigo_completo like '%".$codigo."%' AND descripcion like '%".$nombre."%';";
        }
        else if($codigo!=null AND $nombre==null)
        {
            $where .= "codigo_completo like '%".$codigo."%'";
        }
        else if($codigo==null AND $nombre!=null)
        {
            $where .= "descripcion like '%".$nombre."%';";   
        }

        $query="SELECT
                    id,
                    codigo_completo,
                    descripcion
                FROM
                    diagnosticos_diagnostico
                ".$where;

        try
        {
            $this->dbTurnos->conectar();
            $this->dbTurnos->ejecutarQuery($query);
        }
        catch (Exception $e)
        {
            $this->dbTurnos->desconectar();
            throw new Exception("No se pudo traer los grupos del diagnostico", 201230);            
        }

        $diagnosticos = array();

        for ($f = 0; $f < $this->dbTurnos->querySize; $f++)
        {
            $result = $this->dbTurnos->fetchRow($query);
            $diagnosticos[] = $result;
        }

        $this->dbTurnos->desconectar();

        return $diagnosticos;
    }

}