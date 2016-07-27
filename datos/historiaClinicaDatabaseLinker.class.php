<?php
include_once conexion.'conectionData.php';
include_once conexion.'dataBaseConnector.php';
include_once datos.'utils.php';
include_once datos.'turnoDatabaseLinker.class.php';
include_once datos.'formularioDatabaseLinker.class.php';

class HistoriaClinicaDatabaseLinker
{
    var $dbTurnos;

    function HistoriaClinicaDatabaseLinker()
    {
        $this->dbTurnos = new dataBaseConnector(HOSTLocal,0,DB,USRDBAdmin,PASSDBAdmin);
    }

    
}