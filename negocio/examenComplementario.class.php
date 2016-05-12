<?php
class ExamenComplementario
{
    var $idExamenCompl;

    var $detalle;

    var $observacion;

    function ExamenComplementario($id, $detalle, $observacion)
    {   
        $this->idExamenCompl = $id;
        $this->detalle = $detalle;
        $this->observacion = $observacion;
    }

    public function toStringHTML()
    {
        return $this->detalle."<input type='checkbox' value=".$this->idExamenCompl." checked><div class='textarea-style'>".$this->observacion."</div>";
    }
}