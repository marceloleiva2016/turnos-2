<?php

include_once '/home/web/namespacesAdress.php';
include_once nspcHca . 'hca.class.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
require_once nspcTools . 'FirePHPCore/FirePHP.class.php';
require_once nspcCommons . 'utils.php';

session_start();
$firePhp = FirePHP::getInstance(true);
$firePhp->setEnabled(true);
$firePhp->log($_POST,"POST");

$page = $_POST['page']; // get the requested page 
$limit = $_POST['rows']; // get how many rows we want to have into the grid 
$sidx = $_POST['sidx']; // get index row - i.e. user click to sort 
$sord = $_POST['sord']; // get the direction


$mes = Utils::postIntToPHP( $_POST['mes']);
$ano = Utils::postIntToPHP($_POST['ano']);

$where = "";
if (isset($_POST['filters']))
{
	$obj = json_decode($_POST['filters']);
	
	//aca hay que construir una cadena de elemento AND otro ELEMENTO AND otro
	
	for ($i = 0; $i < count($obj->rules); $i++) {	
		$actualObj = $obj->rules[$i];
		
		$name = $actualObj->field;
		$data = $actualObj->data;
		
		switch ($name) {
			case 'nrodoc':
				$nombre = 'hca.nrodoc';
				$data = Utils::postIntToPHP($data);
				//$data = '%'.$data.'%';
				$where .= " and $nombre = " .$data ." ";
				break;
				
			case 'nombre':
				$nombre = 'fp.nombre';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and $nombre Like '" .$data ."' ";
				break;
				
			case 'ingreso':
				$nombre = 'hca.fecha_ingreso';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and $nombre Like '" .$data ."' ";
				break;
				
			case 'profesional':
				$nombre = 'p.nombre';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and $nombre Like '" .$data ."' ";
				break;
				
			case 'obraSocial':
				$nombre = 'os.cod_busq';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and $nombre Like '" .$data ."' ";
				break;
				
			case 'ip':
				$nombre = 'hca.interv_policial';
				$data = addslashes($data);
				
				$where .= " and $nombre = " .$data ." ";
				break;
					
			case 'destinoDAP':
				$nombre = 'hs.tipo_destino_id';
				$data = Utils::postIntToPHP($data);
				//$data = '%'.$data.'%';
				if($data==6)
				{
					$where .= " and $nombre is null ";	
				}
				else 
				{
					$where .= " and $nombre = " .$data ." ";
				}
				break;
				
			case 'motivo':
				$nombre = 'hca.motivo_consulta';
				$palabras = Utils::stringToArray($data);
				//$firePhp->log($palabras, "Palabras");
				foreach ($palabras as $palabra) {
					if($palabra!="")
					{
						$palabra = addslashes($palabra);
						$palabra = '%'.$palabra.'%';
						
						$where .= " and $nombre Like '" .$palabra ."' ";
					}
				}
				
				
				//$where .= " and $nombre Like '" .$data ."' ";
				break;
				
			case 'diagEgreso':
				//$nombre = 'os.cod_busq';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and ((hs.cod_diagno>100000 and cie.dec10 Like '" .$data ."' ) or (hs.cod_diagno<=100000 and dl.diag_local Like '" .$data ."' )) ";
				//$where .= " and $nombre Like '" .$data ."' ";
				break;
		}
	}
}


$base = new HcaDatabaseLinker();
		

//$json = $internacion->datosResumenInternaciones($cod_centro, $mes, $anio);

$json = $base->pacientesDAPxMes($page, $limit, $sidx, $sord, $ano, $mes,$where);


echo ($json);
?>