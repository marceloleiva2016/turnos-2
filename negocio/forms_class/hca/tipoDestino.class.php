<?php
include_once '/home/web/namespacesAdress.php';
include_once 'hcaDatabaseLinker.class.php';

class TipoDestinoUDP {
	const ALTA = 6;
	const DERIVACION = 7;
	const DERIVACIONint = 8;
	const INTERNACION = 9;
	const TERAPIA = 10;
	const QUIROFANO = 11;
	const OBITO = 12;
        const RETIROVOLUNTARIO = 14;

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
		$lista = $base->listaDestinosHSUDP();
		if (isset($lista[$tipo])) {
			$ret = $lista[$tipo];
		} else {
			throw new OutOfRangeException('Tipo Destino UDP inválido',234);
		}
		
		return $ret;
	}
}//EndClass

class TipoDestinoHS {
	const ALTA = 1;
	const DERIVACION = 2;
	const DERIVACIONint = 3;
	const INTERNACION = 4;
	const OBITO = 5;
        const RETIROVOLUNTARIO = 13;

	/**
	 * 
	 * Devuelve la descripcion del TipoDestinoHS especificado
	 * @param int $tipo
	 * @throws OutOfRangeException
	 * @return string
	 */
	static public function toString($tipo) 
	{
		
		$ret = NULL;
		$base = new HcaDatabaseLinker();
		
		
		$lista = $base->listaDestinosHSDAP();
		//return "hola" . $tipo;
		if (isset($lista[$tipo])) {
			$ret = $lista[$tipo];
		} else {
			throw new OutOfRangeException('Tipo Destino HS inválido',234);
		}
		
		return $ret;
	}
}//EndClass

?>