<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcCommons . 'utils.php';
include_once nspcCommons . 'generalesDataBaseLinker.php';
include_once nspcHca . 'hcaDatabaseLinker.class.php';
include_once nspcHca . 'interconsulta.class.php';
include_once nspcHca . 'observacion.class.php';
include_once nspcHca . 'pendiente.class.php';

class Hca {

	/**
	 * HCA DatabaseLinker
	 * @var HcaDatabaseLinker
	 */
	private $dbLinker;
	
	/**
	 * 
	 * ID del HCA
	 */
	private $id;
	
	/**
	 * 
	 * Motivo de Consulta
	 */
	var $motivoConsulta;
	/**
	 * 
	 * Fecho de Egreso de la UDP
	 */
	var $fechaEgreso;
	
	/**
	 * 
	 * Diagnostico al egreso de la UDP
	 */
	var $diagnosticoEgreso;
	
	/**
	 * 
	 * Profesional que lo egreso
	 */
	var $profesionalEgreso;
	
	/**
	 * 
	 * Fecha de cierre de la HCA
	 */
	var $fechaCierre;
	/**
	 * 
	 * Usuario que cerró la HCA
	 */
	var $usuarioCierre;
	
	/**
	 * 
	 * Ultimo usuario que modifico la HCA
	 */
	var $usuario;
	/**
	 * 
	 * Ultima fecha de modificacion de la HCA
	 */
	var $fechaCreacion;
	
	/**
	 * 
	 * Destino que tuvo el paciente al egresar de la UDP
	 */
	var $tipoDestino;
	
	/**
	 * 
	 * Lista de estudios de Rx
	 * @var array(HcaRx)
	 */
	var $estudiosRx;
	
	/**
	 * 
	 * Lista de estudios de Laboratorio de Sangre
	 * @var array(HcaLaboratorio)
	 */
	var $estudiosLaboratorioSangre;
	/**
	 * 
	 * Lista de estudios de Laboratorio de Orina
	 * @var array(HcaLaboratorio)
	 */
	var $estudiosLaboratorioOrina;
	
	
	/**
	 * 
	 * Lista de estudios de Alta Complejidad
	 * @var array(HcaAltaComplejidad)
	 */
	var $estudiosAltaComplejidad;
	
	/**
	 * 
	 * Lista de Observaciones
	 * @var array(Observacion)
	 */
	var $observaciones;
	
	/**
	 * 
	 * Lista de Interconsultas
	 * @var array(Interconsulta)
	 */
	var $interconsultas;
	/**
	 * 
	 * Lista de Pendientes
	 * @var array(Pendiente)
	 */
	var $pendientes;
	/**
	 * 
	 * Internacion asociada al HCA
	 * @var Internacion
	 */
	var $internacion;
	
	/**
	 * 
	 * tiene los datos de salida de la udp
	 * @var SalidaUDP
	 */
	var $salidaUDP;
	
	/**
	 * 
	 * Contiene la salida del hospital de salida
	 * @var SalidaHS
	 */
	var $salidaHS;
	
	/**
	 * 
	 * Indica si el UDP esta cerrado
	 * @var bool
	 */
	//Tal vez deberiamos poner datos de cierre
	var $cerrado;
	
	/**
	 * 
	 * Indica el tipo de HCA
	 * @var int
	 */
 	var $tipoHCA;
	
 	/**
 	 * 
 	 * Indica el diagnostico con el que se ingreso el paciente
 	 * @var int
 	 */
	var $diagnosticoIngreso;
	
	/**
	 * 
	 * Indica el código de obra social que tiene el paciente al momento de la internacion
	 * @var int
	 */
	var $obraSocial;
	
	/**
	 * 
	 * Inidica si hubo una intervención policial
	 * @var bool
	 */
	var $intervPolicial;
	
	var $codSectorActual;
	
	function __construct()
	{
		$this->estudiosRx = array();
		$this->estudiosLaboratorioSangre = array();
		$this->estudiosLaboratorioOrina = array();
		$this->estudiosAltaComplejidad = array();
		$this->pendientes = array();
		$this->interconsultas = array();
		$this->observaciones = array();
		
		$this->dbLinker = new HcaDatabaseLinker();
		
		$numArgs = func_num_args();
		if ($numArgs == 1) {
			$aux = func_get_arg(0);
			$this->id = func_get_arg(0);
			$this->cargarDatosHCA();			
		}		
	}
	

	/**
	 * 
	 * Devuelve el ID de la HCA
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * 
	 * Carga los datos basicos de la HCA
	 * @throws Exception
	 */
	public function cargarDatosHCA() 
	{
		try {
			$this->dbLinker->cargarDatosHCA($this);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los datos de la HCA ('.$e->getMessage().')', 32143);
		}
	}
	
	/**
	 * 
	 * Carga todos los datos de la HCA
	 * @throws Exception
	 */	
	public function cargarTodoHCA() 
	{
		try {
			$this->internacion->cargarTodo();
			
			$this->cargarLaboratorios();
			
			$this->cargarRx();
			
			$this->cargarAltaComplejidad();
			
			$this->cargarObservaciones();
			
			$this->cargarInterconsultas();
			
			$this->cargarPendientes();
			
			$this->cargarSalidas();
			
		} catch (Exception $e) {
			throw new Exception('Se produjo un error al cargar los datos de la HCA ('.$e->getMessage().')', 32143);
		}
	}
	
	/**
	 * 
	 * Carga los estudios de laboratorio realizados en la HCA
	 * @throws Exception
	 */
	public function cargarLaboratorios() 
	{
		try {
			$this->estudiosLaboratorioSangre = $this->dbLinker->laboratoriosHechos($this->id, TipoLaboratorio::SANGRE);
			$this->estudiosLaboratorioOrina = $this->dbLinker->laboratoriosHechos($this->id, TipoLaboratorio::ORINA);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los estudios de laboratorio ('.$e->getMessage().')', 32143);
		}
	}

	/**
	 * 
	 * Carga los estudios de Rx realizados en la HCA
	 * @throws Exception
	 */
	public function cargarRx() 
	{
		try {
			$this->estudiosRx = $this->dbLinker->rayosHechos($this->id);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los estudios de Rx ('.$e->getMessage().')', 32143);
		}
	}
	
	/**
	 * 
	 * Carga los estudios de Alta Complejidad realizados en la HCA
	 * @throws Exception
	 */
	public function cargarAltaComplejidad() 
	{
		try {
			$this->estudiosAltaComplejidad = $this->dbLinker->altaComplejidadHechos($this->id);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los estudios de Alta Complejidad ('.$e->getMessage().')', 32143);
		}
	}

	/**
	 * 
	 * Carga los datos del paciente en la HCA
	 * @throws Exception
	 */
	public function cargarPaciente() 
	{
		try {
			$this->internacion->cargarPaciente();
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los datos del paciente ('.$e->getMessage().')', 32143);
		}
	}
	
	/**
	 * 
	 * Carga las observaciones en la HCA
	 * @throws Exception
	 */
	public function cargarObservaciones() 
	{
		try {
			$this->observaciones = $this->dbLinker->observaciones($this->id);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar las observaciones ('.$e->getMessage().')', 32143);
		}
	}
		
	/**
	 * 
	 * Carga las interconsultas en la HCA
	 * @throws Exception
	 */
	public function cargarInterconsultas() 
	{
		try {
			$this->interconsultas = $this->dbLinker->interconsultas($this->id);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar las interconsultas ('.$e->getMessage().')', 32143);
		}
	}	

	/**
	 * 
	 * Carga los pendientes en la HCA
	 * @throws Exception
	 */
	public function cargarPendientes() 
	{
		try {
			$this->pendientes = $this->dbLinker->pendientes($this->id);
		} catch (Exception $e) {
			throw new Exception('No se pudieron cargar los pendientes ('.$e->getMessage().')', 32143);
		}
	}	
	
	
	/**
	 * 
	 * Carga las salidas (UDP, HS) en la HCA
	 * @throws Exception
	 */
	public function cargarSalidas() 
	{
		try {
			if ($this->hayEgresoUDP()) {
				
				$this->salidaUDP = $this->dbLinker->salidaHSUDP($this->id);
			}
		} catch (Exception $e) {
			throw new Exception('No se pudo cargar la salida UDP ('.$e->getMessage().')', 32143);
		}
		
		try {
			if($this->tipoHCA== TipoHCA::CAP)
			{
				if ($this->hayEgresoHS()) {
					$this->salidaHS = $this->dbLinker->salidaHSDAP($this->id);
				}
			}
		} catch (Exception $e) {
			throw new Exception('No se pudo cargar la salida HS ('.$e->getMessage().')', 32143);
		}
	}	

	/**
	 * 
	 * Devuelve TRUE si ya se hizo el egreso de la UDP
	 * @throws Exception
	 * @return bool
	 */
	public function hayEgresoUDP() 
	{
		return ($this->dbLinker->hayEgresoHSUDP($this->id));
	}

	/**
	 * 
	 * Devuelve TRUE si ya se hizo el egreso del HS (si no corresponde devuelve FALSE tambien)
	 * @throws Exception
	 * @return bool
	 */
	public function hayEgresoHS() 
	{
		return ($this->dbLinker->hayEgresoHSDAP($this->id));
	}	
		
	/**
	 * 
	 * Devuelve la fecha de ingreso del paciente
	 * @return int $datetime
	 */
	public function getFechaIngreso()
	{
		return ($this->internacion->getFechaIngreso());
	}
	
	/**
	 * 
	 * Dice si en la HCA hay laboratorios de sangre hechos
	 * @return bool
	 */
	public function hayLaboratoriosSangre()
	{
		return count($this->estudiosLaboratorioSangre)>0;
	}
	
	
	/**
	 * 
	 * Dice si en la HCA hay laboratorios de orina hechos
	 * @return bool
	 */
	public function hayLaboratoriosOrina()
	{
		return count($this->estudiosLaboratorioOrina)>0;
	}
	
	/**
	 * 
	 * Dice si en la HCA hay Rayos hechos
	 * @return bool
	 */
	public function hayRayos()
	{
		return count($this->estudiosRx)>0;
	}
	
	/**
	 * 
	 * Dice si en la HCA hay estudios de alta complejidad hechos
	 * @return bool
	 */
	public function hayAltaComplejidad()
	{
		return count($this->estudiosAltaComplejidad)>0;
	}
	
	/**
	 * 
	 * Dice si en la HCA hay observaciones hechas
	 * @return bool
	 */
	public function hayObservaciones()
	{
		return count($this->observaciones)>0;
	}
	
	/**
	 * 
	 * Dice si en la HCA hay interconsultas hechas
	 * @return bool
	 */
	public function hayInterconsultas()
	{
		return count($this->interconsultas)>0;
	}
	
	/**
	 * 
	 * Dice si en la HCA hay pendientes
	 * @return bool
	 */
	public function hayPendientes()
	{
		return count($this->pendientes)>0;
	}
	
	public function getNombreSector()
	{
		return Hca::nombreSector($this->codSectorActual);
	}
	
	/**
	 * 
	 * Devuelve la descripcion del diagnostico de ingreso
	 * @return string
	 */
	public function diagnosticoIngreso()
	{
		$generales = new GeneralesDataBaseLinker();
		return $generales->diagnosticoFromAll($this->diagnosticoIngreso);
	}
	
	public function obraSocail()
	{
		$generales = new GeneralesDataBaseLinker();
		return $generales->obraSocialPorCodigo($this->obraSocial);
	}
	
	public static function nombreSector($codigo)
	{
		switch ($codigo) {
				case 234:
					$texto = "UDP Cirugia";
					break;
					
				case 207:
					$texto = "UDP Clinica";
					break;
					
				case 199:
					$texto = "UDP Traumatologia";
					break;
				
				case 228:
					$texto = "UDP Pediatria";
					break;
					
				case 236:
					$texto = "UDP ORL Materno";
					break;
					
				case 237:
					$texto = "UDP UROLOGIA";
					break;

				case 238:
					$texto = "UDP Pediatria Clínica";
					break;

				case 239:
					$texto = "UDP Cardiología Agudos";
					break;
					
				case 240:
					$texto = "UDP Cardiología";
					break;

				case 249:
					$texto = "UDP Dermatologia";
					break;

				case 105:
					$texto = "UDP Shockroom";
					break;

				case 255:
					$texto = "UDP ORL";
					break;
				
				case 256:
					$texto = "DAP OBSERVACION DENGUE";
					break;

				default:
					//$this->firephp->log($sector,"SECTOR");
					$texto = "N/A";
					break;
		}
		
		return $texto;
	}
}