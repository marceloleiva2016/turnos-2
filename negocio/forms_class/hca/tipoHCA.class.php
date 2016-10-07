<?php
include_once '/home/web/namespacesAdress.php';
include_once 'hcaDatabaseLinker.class.php';

class TipoHCA {
	const UDP = 1;
	const CAP = 2;
	

	/**
	 * 
	 * Devuelve la descripcion del TipoDestinoUDP especificado
	 * @param int $tipo
	 * @throws OutOfRangeException
	 * @return string
	 */
	static public function toString($tipo) 
	{
		$ret = NULL;
		$base = new HcaDatabaseLinker();
		$lista = $base->listaTipoHCA();
		if (isset($lista[$tipo])) {
			$ret = $lista[$tipo];
		} else {
			throw new OutOfRangeException('Tipo HCA inválido',234);
		}
		
		return $ret;
	}
}//EndClass


?>