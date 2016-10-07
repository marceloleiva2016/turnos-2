<?php
include ('/home/web/desa/comunes/config.ini');
include ('/home/web/desa/comunes/funciones.inc');
include '/home/web/desa/comunes/loginweb.php3';

   conectar($host,$usuario,$contrasenia,$base);

// valido usuario y controlo perfil
   $user_id=$PHP_AUTH_USER;
   $password=$PHP_AUTH_PW;
   
include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';

require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);


session_start();
$baseDeDatos = new HcaDatabaseLinker(); 

$baseDeDatos->pacientesUDPToExcel();   