<?php
include_once '/home/web/namespacesAdress.php';
include_once nspcInternacion.'internacion.class.php';
include_once nspcEntidades.'profesional.class.php';
include_once datos.'epicrisisDatabaseLinker.class.php';
include_once 'antecedente.class.php';
include_once 'examenComplementario.class.php';
include_once 'intervencionMenor.class.php';
include_once 'observacion.class.php';
include_once 'medicacionHabitual.class.php';
include_once 'cronicaIntervencion.class.php';

class Epicrisis
{  
	/**
	* Id de la tabla de la epicrisis
	* @var int
	*/
	private $dbLinker;

	/**
	* Id de la tabla de la epicrisis
	* @var int
	*/
	private $id;

	/**
	 * Datos de la internacion
	 * @var Internacion
	 */
	var $internacion;

	/**
	 * Indica el código de obra social que tiene el paciente al momento de la internacion
	 * @var int
	 */
	var $obraSocial;

	/**
	 * Estado de ingreso Constantes 1=Bueno 2=Regular 3=Malo
	 * @var int
	 */
	private $ingreso;

    /**
     * Diagnostico de ingreso del paciente
     * @var Integer
     */
    var $diagnosticoIngreso;

    /**
     * Fecha de egreso de la epicrisis
     * @var Integer
     */
    var $fechaEgreso;

	/**
	* Antecedentes
	* @var Array<Antecedente>
	*/
	var $antecedentes;

    /**
    * Medicacion Habitual
    * @var Array<MedicacionHabitual>
    */
    var $medicacionHabitual;

    /**
    * Cronica de intervenciones
    * @var Array<cronicaIntervenciones>
    */
    var $cronicaIntervenciones;

	/**
	* Examen Complementario
	* @var Array<ExamenComplementario>
	*/
	var $examenComplementario;

	/**
	 * Interneviones menores
	 * @var Array<IntervencionMenor>
	 */
	var $intervencionesMenores;

	/**
	 * Procedimientos Quirurgicos
	 * @var Array<observaciones>
	 */
	var $observaciones;

	/**
	 * Destino del paciente
	 * @var Integer
	 */
	var $destino;

    /**
     * Diagnostico de salida del paciente
     * @var Integer
     */
    var $diagnosticoSalida;

	/**
	 * Profesional de salida
	 * @var Profesional
	 */
	var $profesional;

	function Epicrisis($tipoDoc, $nroDoc, $fecha_ingreso)
	{
		$this->dbLinker = new EpicrisisDatabaseLinker();
		$this->id = $this->dbLinker->getIdEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso);
		$this->internacion = new Internacion();
		$this->antecedentes = array();
        $this->cronicaIntervenciones = array();
        $this->medicacionHabitual = array();
		$this->examenComplementario = array();
		$this->intervencionesMenores = array();
		$this->observaciones = array();
		$this->profesional = new Profesional();
		if($this->id!=NULL)
		{
			$this->cargarDatosEpicrisis();
		}
		else
		{
			$this->crearEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso);
			/*como no se cargo la epicrisis vuelvo a setear el id*/
			$this->id = $this->dbLinker->getIdEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso);
			$this->cargarDatosEpicrisis();
		}
	}

	/**
	 * Carga los datos basicos de la Epicrisis
	 * @throws Exception
	 */
	private function cargarDatosEpicrisis() 
	{
		try 
		{
			$this->dbLinker->cargarDatosEpicrisis($this);
		}
		catch (Exception $e) 
		{
			throw new Exception('No se pudieron cargar todos los datos de la Epicrisis ('.$e->getMessage().')', 32143);
		}
	}
	
	private function crearEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso)
	{
		try 
		{
			$genero = $this->dbLinker->generarEpicrisis($tipoDoc, $nroDoc, $fecha_ingreso);
		}
		catch (Exception $e) 
		{
			throw new Exception('No se pudo crear la Epicrisis ('.$e->getMessage().')', 32143);
		}
	}
    
    /**
	* Carga todos los datos de la Epicrisis
	* @throws Exception
	*/	
    public function getId()
    {
       return $this->id;
    }

    /**
     * seteo la variable de ingreso
     * @param int $value
     */
    public function setIngreso($value)
    {
    	$this->ingreso = $value;
    }

    /**
     * seteo la variable de fechaEgreso
     * @param int $value
     */
    public function setFechaEgreso($value)
    {
        $this->fechaEgreso = $value;
    }

    /**
     * obtengo la fecha de egreso
     * @return array
     */ 
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * obtengo el ingreso
     * @return array
     */	
    public function getIngreso()
    {
    	return $this->ingreso;
    }

    /**
     * seteo la obra social
     * @param int $value
     */
    public function setObraSocial($value)
    {
        $this->obraSocial = $value;
    }

    /**
     * obtengo la obra social
     * @return array
     */ 
    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    /**
     * devuelvo un String con los ingresos
     * @return String
     */
    public function ingresoToHTML()
    {
    	if($this->ingreso==1)
		{
			echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>BUENO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>MALO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>REGULAR";
		}
		elseif($this->ingreso==2)
		{
			echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>BUENO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>MALO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>REGULAR";
		}
		elseif($this->ingreso==3)
		{
			echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>BUENO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>MALO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>REGULAR";
		}
		else
		{
			echo "SIN INGRESAR";
		}
    }

    public function setCodDiagnoIngreso($cod_diagno_ingreso)
    {
        $this->diagnosticoIngreso = $cod_diagno_ingreso;
    }

    public function getCodDiagnoIngreso()
    {
        return $this->diagnosticoIngreso;
    }

    /**
	* Ingreso un antecedente al array
	*/	
    public function agregarAntecedente($idAntecedente, $nombre, $texto)
    {
    	$this->antecedentes[] = new Antecedente($idAntecedente, $nombre, $texto);
    }

    /**
    * Obtengo todos los antecedentes
    * @return Array
    */
    public function getAntecedentes()
    {
    	return $this->antecedentes;
    }

    /**
	* Ingreso un examen al array
	*/	
    public function agregarExamenComplementario($idExamenCompl, $nombre, $texto)
    {
    	$this->examenComplementario[] = new ExamenComplementario($idExamenCompl, $nombre, $texto);
    }

    /**
    * Obtengo todos los examenes Complementarios
    * @return Array
    */
    public function getExamenesComplementarios()
    {
    	return $this->examenComplementario;
    }

    /**
	* Ingreso un examen al array
	*/	
    public function agregarIntervencionMenor($idIntervencion,$descripcion,$seleccionado)
    {
    	$this->intervencionesMenores[] = new IntervencionMenor($idIntervencion,$descripcion,$seleccionado);
    }

    /**
    * Obtengo todos los examenes Complementarios
    * @return Array
    */
    public function getIntervencionesMenores()
    {
    	return $this->intervencionesMenores;
    }

    public function agregarObservaciones($observaciones)
    {
    	$this->observaciones = $observaciones;
    }

    public function getObservaciones()
    {
    	return $this->observaciones;
    }

    public function setDestino($idDestino)
    {
        $this->destino = $idDestino;
    }

    /**
     * devuelvo un String con los destinos
     * @return String
     */
    public function destinoToHTML()
    {
        if($this->destino==1)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        elseif($this->destino==2)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        elseif($this->destino==3)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        elseif($this->destino==4)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        elseif($this->destino==5)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        elseif($this->destino==6)
        {
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>ALTA VOLUNTARIA&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OTRO HOSPITAL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>III NIVEL&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' disabled/><label for='checkbox-1-1'></label>OBITO&nbsp;&nbsp;";
            echo "<input type='checkbox' id='checkbox-1-1' class='regular-checkbox' checked disabled/><label for='checkbox-1-1'></label>FUGA&nbsp;&nbsp;";
        }
        else
        {
            echo "SIN INGRESAR";
        }
    }

    public function setDiagnosticoEgreso($value)
    {
        $this->diagnosticoSalida = $value;
    }

    public function getDiagnosticoEgreso()
    {
        return $this->diagnosticoSalida;
    }

    public function getMedicacionHabitual()
    {
        return $this->medicacionHabitual;
    }

    public function agregarMedicacionHabitual($descripcion)
    {
        $this->medicacionHabitual[] = new MedicacionHabitual($descripcion);
    }

    public function getCronicaIntervenciones()
    {
        return $this->cronicaIntervenciones;
    }

    public function agregarCronicaIntervencion($idIntervencion,$descripcion,$seleccionado)
    {
        $this->cronicaIntervenciones[] = new CronicaIntervencion($idIntervencion,$descripcion,$seleccionado);
    }

}