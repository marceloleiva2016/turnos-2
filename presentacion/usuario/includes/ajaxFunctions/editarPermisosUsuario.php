<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$registro = $obj->confirmarPermisosUsuario($_POST);

$registroCentros = $obj->confirmarCentrosUsuario($_POST);

if($registro->ret==true AND $registroCentros->ret=true)
{
    echo json_encode($registro);
}
else
{
    if($registro->ret==false)
    {
        echo json_encode($registro);
    }
    else
    {
        echo json_encode($registroCentros);
    }
}
?>