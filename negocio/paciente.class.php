<?php
include_once datos.'utils.php';

class Paciente
{
    var $tipodoc;
    var $nrodoc;
    var $nombre;
    var $apellido;
    var $fecha_nac;
    var $edad_actual;
    var $edad_ingreso;
    var $idpais;
    var $idprovincia;
    var $idpartido;
    var $idlocalidad;
    var $cp;
    var $calle_nombre;
    var $calle_numero;
    var $piso;
    var $departamento;
    var $telefono;
    var $telefono2;
    var $esDonante;
    var $email;
    var $fecha_modificacion;
    var $fecha_creacion;
    var $idusuario;

    function Paciente()
    {

    }

//seters

    function setTipodoc($tipodoc)
    {
        $this->tipodoc = $tipodoc;
    }

    function setNrodoc($nrodoc)
    {
        $this->nrodoc = $nrodoc;
    }

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    function setFechaNacimiento($fecha_nac)
    {
        $this->fecha_nac = $fecha_nac;
    }

    function setEdadIngreso($edad)
    {
        $this->edad_ingreso = $edad;
    }

    function setEdadActual($edad)
    {
        $this->edad_actual = $edad;
    }

    function setPais($pais)
    {
        $this->idpais = $pais;
    }

    function setProvincia($prov)
    {
        $this->idprovincia = $prov;
    }

    function setPartido($part)
    {
        $this->idpartido = $part;
    }

    function setLocalidad($loc)
    {
        $this->idlocalidad = $loc;
    }

    function setCP($cp)
    {
        $this->cp = $cp;
    }

    function setCalleNombre($calleNombre)
    {
        $this->calle_nombre = $calleNombre;
    }

    function setCalleNumero($calleNumero)
    {
        $this->calle_numero = $calleNumero;
    }

    function setPiso($piso)
    {
        $this->piso = $piso;
    }

    function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    function setTelefono($telefono1)
    {
        $this->telefono = $telefono1;
    }

    function setTelefono2($telefono2)
    {
        $this->telefono2 = $telefono2;
    }

    function setEsDonante($donante)
    {
        $this->esDonante = $donante;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setFechaModificacion($fecha)
    {
        $this->fecha_modificacion = $fecha;
    }

    function setFechaCreacion($fecha)
    {
        $this->fecha_creacion = $fecha;
    }

//geters

    function getTipoDoc()
    {
        return $this->tipodoc;
    }

    function getNrodoc()
    {
        return $this->nrodoc;
    }

    function getNombre()
    {
        return $this->nombre;
    }

    function getApellido()
    {
        return $this->apellido;
    }

    function getSexo()
    {
        return $this->sexo;
    }

    function getSexoLargo()
    {
        if($this->sexo = "M")
        {
            return "MASCULINO";
        }
        else
        {
            return "FEMENINO";
        }
    }

    function getFechaNacimiento()
    {
        return $this->fecha_nac;
    }

    function getEdadActual()
    {
        return $this->edad_actual;
    }

    function getEdadAlIngreso()
    {
        return $this->edad_ingreso;
    }

    function getPais()
    {
        return $this->idpais;
    }

    function getProvincia()
    {
        return $this->idprovincia;
    }

    function getPartido()
    {
        return $this->idpartido;
    }

    function getLocalidad()
    {
        return $this->idlocalidad;
    }

    function getCP()
    {
        return $this->cp;
    }

    function getCalleNombre()
    {
        return $this->calle_nombre;
    }

    function getCalleNumero()
    {
        return $this->calle_numero;
    }

    function getPiso()
    {
        return $this->piso;
    }

    function getDepartamento()
    {
        return $this->departamento;
    }

    function getTelefono()
    {
        return $this->telefono;
    }

    function getTelefono2()
    {
        return $this->telefono2;
    }

    function getDonante()
    {
        return $this->esDonante;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getFechaModificacion($fecha)
    {
        return $this->fecha_modificacion;
    }

    function getFechaCreacion($fecha)
    {
        return $this->fecha_creacion;
    }
}
?>