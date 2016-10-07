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
				
				
			case 'cod_secfis':
				$data = Utils::postIntToPHP($data);
				switch ($data) {
					case 1: //UDP Clinica
						$cod_secfis = 207;
						break;
					case 2: //UDP Cx
						$cod_secfis = 234;
						break;
					case 3: //UDP Traumato
						$cod_secfis = 199;
						break;
					case 4: //UDP Pediatria
						$cod_secfis = 228;
						break;
					case 5: //UDP ORL Materno
						$cod_secfis = 236;
						break;
					case 6: //UDP Urologia
						$cod_secfis = 237;
						break;
					case 7: //UDP Pediatria Clinica
						$cod_secfis = 238;
						break;
						
					case 8: //UDP Cardiologia Agudos
						$cod_secfis = 239;
						break;
						
					case 9: //UDP Cardiologia
						$cod_secfis = 240;
						break;

					case 11: //UDP Dermatologia
						$cod_secfis = 249;
						break;

					case 12: //UDP Shockroom
						$cod_secfis = 105;
						break;
					
					case 13: //UDP ORL
						$cod_secfis = 255;
						break;

					default:
						throw Exception('El sector indicado en el filtro esta fuera de rango');
						break;
				}
				$where .= " AND (
								 (
								 	sfs.cod_secfis is null AND
								 	sfi.cod_secfis = ".Utils::phpIntToSQL($cod_secfis)."
								 ) OR
								 sfs.cod_secfis = ".Utils::phpIntToSQL($cod_secfis)."
				)";
				break;
				
			case 'destinoUDP':
				$nombre = 'hs.tipo_destino_id';
				$data = Utils::postIntToPHP($data);
				//$data = '%'.$data.'%';
				if($data==13)
				{
					$where .= " and $nombre is null ";	
				}
				else 
				{
					$where .= " and $nombre = " .$data ." ";
				}
				break;
				
			/*case 'destinoHS':
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
				break;*/
				
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
			
				
			/*case 'diagEgreso':
				$nombre = 'dl.diag_local';
				$data = addslashes($data);
				$data = '%'.$data.'%';
				$where .= " and $nombre Like '" .$data ."' ";
				break;*/
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

$json = $base->pacientesUDPxMes($page, $limit, $sidx, $sord, $ano, $mes,$where);


echo ($json);