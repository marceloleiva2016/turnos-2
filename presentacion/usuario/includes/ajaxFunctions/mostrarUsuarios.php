<?php
include_once '../../../../namespacesAdress.php';
include_once datos.'usuarioDatabaseLinker.class.php';

$obj = new UsuarioDatabaseLinker();

$arr= array();

if($_POST["_search"] == "true")
{
    $arr = json_decode($_POST['filters'], true);
}

$ret = $obj->getUsuariosJson($_POST['entidad'], $_REQUEST['page'], $_REQUEST['rows'], $arr);
	
echo $ret;

?>